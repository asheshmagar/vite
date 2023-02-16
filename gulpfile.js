const request = require( 'request' );
const fs = require( 'fs' );
const zip = require( 'gulp-zip' );
const { exec } = require( 'child_process' );
const pkg = JSON.parse( fs.readFileSync( './package.json' ) );
const { series, dest, src, parallel } = require( 'gulp' );
const jsonminfy = require( 'gulp-json-minify' );
const { argv } = require( 'yargs' );

/* eslint-disable no-console */

const files = {
	inc: {
		src: 'inc/**/*',
		dest: `build/${ pkg.name }/inc`,
	},
	assets: {
		src: [ 'assets/**/*', '!assets/js/**/*', '!assets/scss/**/*' ],
		dest: `build/${ pkg.name }/assets`,
	},
	bootstrap: {
		src: 'bootstrap/**/*',
		dest: `build/${ pkg.name }/bootstrap`,
	},
	templateParts: {
		src: 'template-parts/**/*',
		dest: `build/${ pkg.name }/template-parts`,
	},
	composer: {
		src: [ 'composer.json', 'composer.lock' ],
		dest: `build/${ pkg.name }/`,
	},
	other: {
		src: [ 'README.md', 'style.css', '*.php' ],
		dest: `build/${ pkg.name }/`,
	},
};

const filesToCompress = [
	'build/**/*',
	'!build/**/composer.lock',
	'!build/**/composer.json',
	'!build/**/assets/js',
	'!build/**/assets/scss',
	'!build/**/node_modules/**/*',
];

const log = function( error ) {
	console.log( error.toString() );
	this.emit( 'end' );
};

const execErrorLog = function( error ) {
	if ( error ) console.log( error );
};

const copy = [
	() => src( files.inc.src ).pipe( dest( files.inc.dest ) ).on( 'error', log ),
	() => src( files.assets.src ).pipe( dest( files.assets.dest ) ).on( 'error', log ),
	() => src( files.bootstrap.src ).pipe( dest( files.bootstrap.dest ) ).on( 'error', log ),
	() => src( files.templateParts.src ).pipe( dest( files.templateParts.dest ) ).on( 'error', log ),
	() => src( files.composer.src ).pipe( dest( files.composer.dest ) ).on( 'error', log ),
	() => src( files.other.src ).pipe( dest( files.other.dest ) ).on( 'error', log ),
];

const minifyJSON = () => src( `build/${ pkg.name }/assets/json/*.json` ).pipe( jsonminfy() ).pipe( dest( `build/${ pkg.name }/assets/json` ) ).on( 'error', log );

const composer = () => exec( `cd build/${ pkg.name } && composer install --no-dev --optimize-autoloader`, execErrorLog );

const pot = () => exec( 'composer make-pot', execErrorLog );

const yarn = () => exec( 'yarn build', execErrorLog );

const clean = {
	build: () => exec( 'rm -rf build', execErrorLog ),
	release: () => exec( 'rm -rf release', execErrorLog ),
};

const compress = parallel( [
	() => src( filesToCompress ).pipe( zip( `${ pkg.name }.zip` ) ).pipe( dest( 'release' ) ).on( 'error', log ),
	() => src( filesToCompress ).pipe( zip( `${ pkg.name }-${ pkg.version }.zip` ) ).pipe( dest( 'release' ) ).on( 'error', log ),
] );

const release = series(
	clean.build,
	clean.release,
	yarn,
	pot,
	copy,
	composer,
	minifyJSON,
	compress,
	clean.build,
);

const fetchExternal = {
	googleFonts: () => request( 'https://gwfh.mranftl.com/api/fonts', ( error, response, body ) => {
		if ( ! error && response.statusCode === 200 ) {
			const fonts = JSON.parse( body )
				.map( function( a ) {
					return {
						...a,
						label: a.family,
						value: a.family,
					};
				} );
			fs.writeFile(
				'assets/json/google-fonts.json',
				JSON.stringify(
					[
						{
							family: 'Default',
							variants: [ 'regular', '100', '200', '300', '400', '500', '600', '700', '800', '900' ],
							value: 'default',
							defVariant: 'regular',
							id: 'default',
							label: 'Default',
						}, {
							family: 'Inherit',
							variants: [ 'regular', '100', '200', '300', '400', '500', '600', '700', '800', '900' ],
							value: 'inherit',
							defVariant: 'regular',
							id: 'inherit',
							label: 'Inherit',
						}, ...fonts ],
					null,
					2 ),
				function( err ) {
					if ( ! err ) {
					// eslint-disable-next-line no-console
						console.log( 'Google fonts updated!' );
					}
				} );
		}
	} ),
	fontawesome: () => request( 'https://raw.githubusercontent.com/FortAwesome/Font-Awesome/6.x/metadata/icons.json', function( error, response, body ) {
		if ( ! error && response.statusCode === 200 ) {
			const icons = JSON.parse( body );
			const allIcons = Object.keys( icons ).reduce( function( acc, crr ) {
				const svg = icons[ crr ].svg?.brands || icons[ crr ].svg?.solid;
				acc[ crr ] = `<svg class="${ crr } %s" height="%d" width="%d" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="${ svg.viewBox.join( ' ' ) }"><path fill="currentColor" d="${ svg.path }"></path></svg>`;
				return acc;
			}, {} );
			fs.writeFile( 'assets/json/font-awesome.json', JSON.stringify( allIcons, null, 2 ), function( err ) {
				if ( ! err ) {
					// eslint-disable-next-line no-console
					console.log( 'Fontawesome library updated!' );
				}
			} );
		}
	} ),
};

exports.fetchExternal = series(
	fetchExternal.googleFonts,
	fetchExternal.fontawesome,
);
exports.release = release;

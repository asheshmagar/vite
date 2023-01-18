const request = require( 'request' );
const fs = require( 'fs' );
const zip = require( 'gulp-zip' );
const { exec } = require( 'child_process' );
const pkg = JSON.parse( fs.readFileSync( './package.json' ) );
const { series, dest, src, parallel } = require( 'gulp' );

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

const copy = [
	() => src( files.inc.src ).pipe( dest( files.inc.dest ) ),
	() => src( files.assets.src ).pipe( dest( files.assets.dest ) ),
	() => src( files.bootstrap.src ).pipe( dest( files.bootstrap.dest ) ),
	() => src( files.templateParts.src ).pipe( dest( files.templateParts.dest ) ),
	() => src( files.composer.src ).pipe( dest( files.composer.dest ) ),
	() => src( files.other.src ).pipe( dest( files.other.dest ) ),
];

const composer = () => exec( `cd build/${ pkg.name } && composer install --no-dev --optimize-autoloader` );

const pot = () => exec( 'composer make-pot' );

const yarn = () => exec( 'yarn build' );

const clean = {
	build: () => exec( 'rm -rf build' ),
	release: () => exec( 'rm -rf release' ),
};

const compress = parallel( [
	() => src( filesToCompress ).pipe( zip( `${ pkg.name }.zip` ) ).pipe( dest( 'release' ) ),
	() => src( filesToCompress ).pipe( zip( `${ pkg.name }-${ pkg.version }.zip` ) ).pipe( dest( 'release' ) ),
] );

const release = series(
	clean.build,
	clean.release,
	yarn,
	pot,
	copy,
	composer,
	compress,
	clean.build,
);

const fetchExternal = {
	googleFonts: () => request( 'https://google-webfonts-helper.herokuapp.com/api/fonts', ( error, response, body ) => {
		if ( ! error && response.statusCode === 200 ) {
			const fonts = JSON.parse( body )
				.sort( function( a, b ) {
					return ( b?.popularity ?? 0 ) - ( a?.popularity ?? 0 );
				} )
				.map( function( a ) {
					return {
						...a,
						label: a.family,
						value: a.family,
					};
				} );
			fs.writeFile( 'assets/json/google-fonts.json', JSON.stringify( fonts, null, 2 ), function( err ) {
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

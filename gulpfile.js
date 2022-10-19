const request = require( 'request' );
const fs = require( 'fs' );
const gulp = require( 'gulp' );
const pkg = JSON.parse( fs.readFileSync( './package.json' ) );

const fetchGoogleFonts = () => request( 'https://google-webfonts-helper.herokuapp.com/api/fonts', ( error, response, body ) => {
	if ( ! error && response.statusCode === 200 ) {
		const fonts = JSON.parse( body )
			.sort( function( a, b ) {
				return a.family.localeCompare( b.family );
			} );
		fs.writeFile( 'assets/json/google-fonts.json', JSON.stringify( fonts, null, 2 ), function( err ) {
			if ( ! err ) {
				// eslint-disable-next-line no-console
				console.log( 'Google fonts updated!' );
			}
		} );
	}
} );

const fetchFontAwesome = () => request( 'https://raw.githubusercontent.com/FortAwesome/Font-Awesome/6.x/metadata/icons.json', function( error, response, body ) {
	if ( ! error && response.statusCode === 200 ) {
		const icons = JSON.parse( body );
		const newIcons = Object.keys( icons ).reduce( function( acc, crr ) {
			const svg = icons[ crr ].svg?.brands || icons[ crr ].svg?.solid;
			acc[ crr ] = `<svg class="${ crr } %s" height="%d" width="%d" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="${ svg.viewBox.join( ' ' ) }"><path fill="currentColor" d="${ svg.path }"></path></svg>`;
			return acc;
		}, {} );
		fs.writeFile( 'assets/json/font-awesome.json', JSON.stringify( newIcons, null, 2 ), function( err ) {
			if ( ! err ) {
				// eslint-disable-next-line no-console
				console.log( 'Fontawesome library updated!' );
			}
		} );
	}
} );

exports.fetchGoogleFonts = fetchGoogleFonts;
exports.fetchFontAwesome = fetchFontAwesome;

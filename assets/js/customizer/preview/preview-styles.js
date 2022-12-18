import { isEmpty, isNull, isObject, isUndefined, uniq } from 'lodash';
import $ from 'jquery';

class PreviewStyles {
	#devices = [ 'desktop', 'tablet', 'mobile' ];
	#sides = [ 'top', 'right', 'bottom', 'left' ];
	#radiusSides = [ 'top-left', 'top-right', 'bottom-right', 'bottom-left' ];
	#css = '';
	#fonts = {};
	#configs;
	#api;

	constructor( api, configs = [] ) {
		this.#configs = configs;
		this.#api = api;
		this.#init();
	}

	#attach( selector, properties, state = 'normal' ) {
		if ( ! selector || ! properties ) return {};

		state = 'normal' === state ? '' : `:${ state }`;
		selector = Array.isArray( selector ) ? selector : [ selector ];

		selector = selector.reduce( ( acc, curr, i, arr ) => {
			if ( arr?.length - 1 === i ) {
				acc += curr + state;
			} else {
				acc += curr + state + ',';
			}
			return acc;
		}, '' );

		return { [ selector ]: properties };
	}

	#typography( selector, typography ) {
		const css = {
			desktop: '',
			tablet: '',
			mobile: '',
		};
		selector = Array.isArray( selector ) ? selector.join( ', ' ) : selector;
		for ( const device of this.#devices ) {
			if ( 'desktop' === device ) {
				if ( typography?.family ) {
					const family = typography.family;
					if ( ! [ 'Default', 'Inherit' ].includes( family ) ) {
						if ( ! this.#fonts?.[ family ] ) {
							this.#fonts[ family ] = [];
						}
						this.#fonts[ family ]?.push( typography?.weight ?? 400 );
					}
					css[ device ] += `font-family: ${ family };`;
				}
				if ( typography?.weight ) css[ device ] += `font-weight: ${ typography.weight };`;
				if ( typography?.style ) css[ device ] += `font-style: ${ typography.style };`;
				if ( typography?.transform ) css[ device ] += `text-transform: ${ typography.transform };`;
			}

			if ( typography?.size?.[ device ]?.value ) css[ device ] += `font-size: ${ typography.size[ device ].value }${ typography?.size?.[ device ]?.unit ?? 'px' };`;

			let lineHeightUnit = typography?.lineHeight?.[ device ]?.unit ?? 'px';
			lineHeightUnit = '-' === lineHeightUnit ? '' : lineHeightUnit;

			if ( typography?.lineHeight?.[ device ]?.value ) css[ device ] += `line-height: ${ typography.lineHeight[ device ].value }${ lineHeightUnit };`;
			if ( typography?.letterSpacing?.[ device ]?.value ) css[ device ] += `letter-spacing: ${ typography.letterSpacing[ device ].value }${ typography?.letterSpacing?.[ device ]?.unit ?? 'px' };`;
		}

		return {
			desktop: this.#attach( selector, css.desktop ),
			tablet: this.#attach( selector, css.tablet ),
			mobile: this.#attach( selector, css.mobile ),
		};
	}

	#border( selector, border ) {
		const css = {
			desktop: '',
			tablet: '',
			mobile: '',
		};

		if ( ! border?.style || 'none' === border.style ) return css;

		let properties = '', propertiesHover = '';

		properties += `border-style: ${ border.style };`;

		if ( border?.color?.normal ) properties += `border-color: ${ border.color.normal };`;
		if ( border?.color?.hover ) propertiesHover += `border-color: ${ border.color.hover };`;

		properties += Object.value( this.#dimensions( '', 'border', border?.width, '%property-%side-width' ) ).join( '' );
		css.desktop = [ ...( this.#attach( selector, properties ) ), ...( this.#attach( selector, propertiesHover, 'hover' ) ) ];

		return css;
	}

	#dimensions( selector, property, dimensions, pattern = '%property-%side' ) {
		selector = Array.isArray( selector ) ? selector.join( ', ' ) : selector;
		property = Array.isArray( property ) ? property : [ property ];
		let css = '', sides = this.#sides;

		if ( Object.keys( dimensions ).some( d => this.#radiusSides.includes( d ) ) ) {
			sides = this.#radiusSides;
		}

		for ( const prop of property ) {
			for ( const side of sides ) {
				const cssProp = pattern.replace( '%property', prop ).replace( '%side', side );
				const unit = dimensions?.unit ?? 'px';
				if ( dimensions?.side && 'auto' !== dimensions?.side ) css += `${ cssProp }: ${ dimensions.side }${ unit };`;
			}
		}

		if ( css ) {
			return this.#attach( selector, css );
		}
	}

	#responsiveDimensions( selector, property, dimensions, pattern = '%property-%side' ) {
		const css = {
			desktop: {},
			tablet: {},
			mobile: {},
		};

		if ( Object.keys( dimensions ).some( d => this.#devices.includes( d ) ) ) {
			for ( const device of this.#devices ) {
				if ( dimensions?.[ device ] ) {
					css[ device ] = this.#dimensions( selector, property, dimensions[ device ], pattern );
				}
			}
		} else {
			css.desktop = this.#dimensions( selector, property, dimensions, pattern );
		}

		return css;
	}

	#background( selector, background ) {
		const css = {
			desktop: '',
			tablet: '',
			mobile: '',
		};

		if ( ! background?.type ) return css;

		if ( 'color' === background.type ) {
			if ( background?.color ) css.desktop += `background-color: ${ background.color };`;
		} else if ( 'gradient' === background.type ) {
			if ( background?.gradient ) css.desktop += `background: ${ background.gradient };`;
		} else if ( 'image' === background.type ) {
			if ( background?.color ) css.desktop += `background-color: ${ background.color };`;
			if ( background?.image ) css.desktop += `background-image: url( ${ background.image } );`;
			for ( const device of this.#devices ) {
				if ( background?.position?.device ) css[ device ] += `background-position: ${ background.position.device };`;
				if ( background?.size?.device ) css[ device ] += `background-size: ${ background.size.device };`;
				if ( background?.repeat?.device ) css[ device ] += `background-repeat: ${ background.repeat.device };`;
				if ( background?.attachment?.device ) css[ device ] += `background-attachment: ${ background.attachment.device };`;
			}
		}

		return {
			desktop: css?.desktop ? this.#attach( selector, css.desktop ) : [],
			tablet: css?.tablet ? this.#attach( selector, css.tablet ) : [],
			mobile: css?.mobile ? this.#attach( selector, css.mobile ) : [],
		};
	}

	#range( selector, property, range ) {
		const css = {
			desktop: '',
			tablet: '',
			mobile: '',
		};

		if ( ! isObject( range ) || ! selector || ! property ) return css;
		property = Array.isArray( property ) ? property : [ property ];

		if ( Object.keys( range ).some( d => this.#devices.includes( d ) ) ) {
			for ( const device of this.#devices ) {
				if ( range?.[ device ]?.value ) {
					const props = property.reduce( ( acc, prop ) => {
						prop += `${ prop }: ${ range[ device ].value }${ range?.[ device ]?.unit ?? 'px' };`;
						return prop;
					}, '' );
					css[ device ] += props;
				}
			}
		} else {
			if ( range?.value ) { // eslint-disable-line no-lonely-if
				const props = property.reduce( ( acc, prop ) => {
					prop += `${ prop }: ${ range.value }${ range?.unit ?? 'px' };`;
					return prop;
				}, '' );
				css.desktop += props;
			}
		}

		return {
			desktop: css?.desktop ? this.#attach( selector, css.desktop ) : {},
			tablet: css?.tablet ? this.#attach( selector, css.tablet ) : {},
			mobile: css?.mobile ? this.#attach( selector, css.mobile ) : {},
		};
	}

	#color( selector, property, color ) {
		const css = {
			desktop: '',
			tablet: '',
			mobile: '',
		};

		if ( ! color?.value ) return css;

		if ( isObject( color ) ) {
			for ( const key in color ) {
				if ( color[ key ] ) {
					css.desktop = {
						...( this.#attach( selector, key.startsWith( '--' ) ? key : property ) + ':' + color[ key ] + ';', key.startsWith( '--' ) ? 'normal' : key ),
					};
				}
			}
		} else {
			css.desktop = this.#attach( selector, property + ':' + color + ';' );
		}

		return css;
	}

	#common( selector, property, value ) {
		const css = {
			desktop: [],
			tablet: [],
			mobile: [],
		};

		if ( ! value ) return css;

		if ( isObject( value ) ) {
			if ( Object.keys( value ).some( d => this.#devices.includes( d ) ) ) {
				for ( const device of this.#devices ) {
					if ( value[ device ] ) {
						css[ device ] = this.#attach( selector, property + ':' + value[ device ] + ';' );
					}
				}
				return css;
			}
		}

		return {
			desktop: this.#attach( selector, property + ':' + value + ';' ),
			tablet: [],
			mobile: [],
		};
	}

	#fontCSS() {
		if ( isEmpty( this.#fonts ) ) return;
		const $head = $( 'head' );
		const base = 'https://fonts.googleapis.com/css';
		for ( const family in this.#fonts ) {
			const fontFamily = family.replace( / /g, '+' );
			const variants = uniq( this.#fonts[ family ] );

			$( `link[id="vite-google-fonts-${ fontFamily }"]` ).remove();
			const url = `${ base }?family=${ fontFamily }:${ variants.join( ',' ) }&display=swap`;
			$head.append( `<link rel="stylesheet" id="vite-google-fonts-${ fontFamily }" href="${ url }" type="text/css" media="all">` );
		}
	}

	#makeCSS( id, data ) {
		if ( isEmpty( data ) ) return;
		let desktop = '', tablet = '', mobile = '';

		for ( const device in data ) {
			if ( isEmpty( data[ device ] ) ) continue;
			for ( const selector in data[ device ] ) {
				if ( isEmpty( data[ device ][ selector ] ) ) continue;
				const props = data[ device ][ selector ];
				if ( 'desktop' === device ) {
					desktop += `${ selector } { ${ props } }`;
				} else if ( 'tablet' === device ) {
					tablet += `${ selector } { ${ props } }`;
				} else if ( 'mobile' === device ) {
					mobile += `${ selector } { ${ props } }`;
				}
			}
		}

		id = id = id.replace( /[\[\]']+/g, '-' );
		const $head = $( 'head' );

		$( `style#${ id }` ).remove();
		const $style = $( `<style id="${ id }">${ desktop }</style>` );

		if ( ! isEmpty( tablet ) ) {
			$style.append( `@media (max-width: 721px) { ${ tablet } }` );
		}
		if ( ! isEmpty( mobile ) ) {
			$style.append( `@media (max-width: 481px) { ${ mobile } }` );
		}
		$head.append( $style );
	}

	#init() {
		if ( isEmpty( this.#configs ) ) return;
		for ( const key in this.#configs ) {
			const { selector = '', property = '', type, pattern = '%property-%side' } = this.#configs[ key ];
			this.#api( key, value => {
				value.bind( newValue => {
					if ( isNull( newValue ) || isUndefined( newValue ) ) return;
					switch ( type ) {
						case 'vite-dimensions':
							this.#makeCSS( key, this.#responsiveDimensions( selector, property, newValue, pattern ) );
							break;
						case 'vite-border':
							this.#makeCSS( key, this.#border( selector, newValue ) );
							break;
						case 'vite-typography':
							this.#makeCSS( key, this.#typography( selector, newValue ) );
							break;
						case 'vite-color':
							this.#makeCSS( key, this.#color( selector, property, newValue ) );
							break;
						case 'vite-background':
							this.#makeCSS( key, this.#background( selector, newValue ) );
							break;
						case 'vite-slider':
							this.#makeCSS( key, this.#range( selector, property, newValue ) );
							break;
						default:
							this.#makeCSS( key, this.#common( selector, property, newValue ) );
					}
					this.#fontCSS();
				} );
			} );
		}
	}
}

export default PreviewStyles;

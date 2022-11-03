import $ from 'jquery';
import _ from 'lodash';

{
	let SETTINGS = window._VITE_CUSTOMIZER_PREVIEW_?.settings || {};
	SETTINGS = _.pickBy( SETTINGS, ( value ) => value.hasOwnProperty( 'selectors' ) );

	const STATES = [
		'hover',
		'focus',
		'active',
		'visited',
	];

	const DEVICES = [
		'desktop',
		'tablet',
		'mobile',
	];

	const SIDES = [
		'top',
		'right',
		'bottom',
		'left',
	];

	const api = wp.customize;

	const isObject = ( value ) => Object.prototype.toString.call( value ) === '[object Object]';

	const isNullOrUndefined = ( value ) => [ 'null', 'undefined' ].includes( typeof value );

	const isScalar = ( value ) => [ 'string', 'number', 'boolean' ].includes( typeof value );

	const makeCSS = ( selectors, properties, value ) => {
		if ( isNullOrUndefined( value ) ) {
			return '';
		}

		if ( properties?.length ) {
			return selectors.join( ',' ) + '{' + properties.map( p => p + ':' + value + ';' ).join( '' ) + '}';
		}

		return selectors.join( ',' ) + '{' + value + '}';
	};

	const dimensionCSS = ( value = [] ) => {
		const units = value?.unit ?? 'px';
		let css = ( value?.top ?? 0 ) + units + ' ';
		css += ( value?.right ?? 0 ) + units + ' ';
		css += ( value?.bottom ?? 0 ) + units + ' ';
		css += ( value?.left ?? 0 ) + units;
		return css;
	};

	const backgroundCSS = ( value = [] ) => {
		let css = '';
		if ( value?.image ) {
			css = `background: url(${ value?.image }) ${ value?.position ?? 'center center' } / ${ value?.size ?? 'cover' } ${ value?.repeat ?? 'no-repeat' } ${ value?.attachment ?? 'scroll' } ${ value?.color ?? 'transparent' };`;
		} else {
			if ( value?.position ) {
				css = `background-position: ${ value?.position };`;
			}
			if ( value?.size ) {
				css = `background-size: ${ value?.size };`;
			}
			if ( value?.repeat ) {
				css = `background-repeat: ${ value?.repeat };`;
			}
			if ( value?.attachment ) {
				css = `background-attachment: ${ value?.attachment };`;
			}
		}
		return css;
	};

	const typographyCSS = ( value = [] ) => {
		let css = '';
		if ( value?.family ) {
			if ( value.family !== 'System Default' ) {
				css += `font-family: ${ value.family };`;
			} else {
				css += 'font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;';
			}
		}
		if ( value?.weight ) {
			css += `font-weight: ${ value.weight };`;
		}
		if ( value?.style ) {
			css += `font-style: ${ value.style };`;
		}
		if ( value?.size ) {
			const size = value?.size;
			if ( size?.value ) {
				css += `font-size: ${ size.value }${ size?.unit ?? 'px' };`;
			}
		}
		if ( value?.lineHeight ) {
			const lineHeight = value.lineHeight;
			lineHeight.unit = lineHeight.unit ?? '';
			lineHeight.unit = '-' === lineHeight.unit ? '' : lineHeight.unit;
			if ( lineHeight?.value ) {
				css += `line-height: ${ lineHeight.value + lineHeight.unit };`;
			}
		}
		if ( value?.letterSpacing ) {
			const letterSpacing = value?.letterSpacing;
			if ( letterSpacing?.value ) {
				css += `letter-spacing: ${ letterSpacing.value }${ letterSpacing?.unit ?? 'px' };`;
			}
		}
		if ( value?.textTransform ) {
			css += `text-transform: ${ value?.textTransform };`;
		}
		return css;
	};

	const borderCSS = ( value = [] ) => {
		let css = '';
		if ( value?.radius ) {
			const radius = value?.radius;
			if ( radius?.value ) {
				radius.unit = radius.unit ?? 'px';
				css += `border-radius: ${ radius.value + radius.unit };`;
			}
		}

		if ( value?.style && 'none' !== value?.style ) {
			css += `border-style: ${ value?.style };`;
			if ( value?.width ) {
				const width = value?.width;
				if ( width?.value ) {
					width.unit = width.unit ?? 'px';
					css += `border-width: ${ width.value + width.unit };`;
				}
			}
			if ( value?.color?.normal ) {
				css += `border-color: ${ value?.color?.normal };`;
			}
		}
		return css;
	};

	for ( let id in SETTINGS ) {
		const { selectors, properties = [], type, input_attrs: attrs = {} } = SETTINGS[ id ];
		api( id, value => {
			value.bind( newValue => {
				if ( isNullOrUndefined( newValue ) ) {
					return;
				}

				const css = {
					desktop: '',
					tablet: '',
					mobile: '',
				};

				let keys;

				let font = '';

				switch ( type ) {
					case 'vite-dimensions':
						keys = Object.keys( newValue );
						if ( keys.some( k => SIDES.includes( k ) ) ) {
							css.desktop += makeCSS( selectors, properties, dimensionCSS( newValue ) );
						} else if ( keys.some( k => DEVICES.includes( k ) ) ) {
							for ( const device of DEVICES ) {
								css[ device ] += makeCSS( selectors, properties, dimensionCSS( newValue[ device ] ) );
							}
						}
						break;
					case 'vite-slider':
						if ( isObject( newValue ) ) {
							keys = Object.keys( newValue );
							if ( keys.some( k => DEVICES.includes( k ) ) ) {
								for ( const device of DEVICES ) {
									if ( newValue?.[ device ] && isScalar( newValue?.[ device ] ) ) {
										css[ device ] += makeCSS( selectors, properties, newValue[ device ] );
									}
									if ( newValue?.[ device ]?.value ) {
										let unit = newValue?.[ device ]?.unit ?? 'px';
										unit = unit === '-' ? '' : unit;
										css[ device ] += makeCSS( selectors, properties, newValue[ device ].value + ( ! attrs?.noUnits ? unit : '' ) );
									}
								}
							} else {
								let unit = newValue?.unit ?? 'px';
								unit = unit === '-' ? '' : unit;
								css.desktop += makeCSS( selectors, properties, newValue.value + ( ! attrs?.noUnits ? unit : '' ) );
							}
						} else {
							css.desktop += makeCSS( selectors, properties, newValue );
						}
						break;
					case 'vite-background':
						const backgroundType = newValue?.type ?? 'color';
						switch ( backgroundType ) {
							case 'color':
								css.desktop += makeCSS( selectors, [], `background-color: ${ newValue?.color ?? 'transparent' };` );
								break;
							case 'gradient':
								if ( newValue?.gradient ) {
									css.desktop += makeCSS( selectors, [], `background:${ newValue.gradient };` );
								}
								break;
							case 'image':
								delete newValue?.gradient;

								const deviceArray = {
									desktop: {},
									tablet: {},
									mobile: {},
								};

								for ( const device of DEVICES ) {
									for ( const [ k, v ] of Object.entries( newValue ) ) {
										if ( v && isScalar( v ) ) {
											deviceArray.desktop[ k ] = v;
											continue;
										}

										if ( v?.[ device ] ) {
											deviceArray[ device ][ k ] = v[ device ];
										}
									}
								}

								if ( Object.keys( deviceArray.desktop ).length ) {
									css.desktop += makeCSS( selectors, [], backgroundCSS( deviceArray.desktop ) );
								}
								if ( Object.keys( deviceArray.tablet ).length ) {
									css.tablet += makeCSS( selectors, [], backgroundCSS( deviceArray.tablet ) );
								}
								if ( Object.keys( deviceArray.mobile ).length ) {
									css.mobile += makeCSS( selectors, [], backgroundCSS( deviceArray.mobile ) );
								}
								break;
						}
						break;
					case 'vite-typography':
						const deviceArray = {
							desktop: {},
							tablet: {},
							mobile: {},
						};

						for ( const device of DEVICES ) {
							for ( const [ k, v ] of Object.entries( newValue ) ) {
								if ( v && isScalar( v ) ) {
									deviceArray.desktop[ k ] = v;
									continue;
								}
								if ( v?.[ device ] ) {
									deviceArray[ device ][ k ] = v[ device ];
								}
							}
						}

						if ( Object.keys( deviceArray.desktop ).length ) {
							css.desktop += makeCSS( selectors, [], typographyCSS( deviceArray.desktop ) );
						}

						if ( Object.keys( deviceArray.tablet ).length ) {
							css.tablet += makeCSS( selectors, [], typographyCSS( deviceArray.tablet ) );
						}

						if ( Object.keys( deviceArray.mobile ).length ) {
							css.mobile += makeCSS( selectors, [], typographyCSS( deviceArray.mobile ) );
						}

						if ( newValue?.family ) {
							if ( ! [ 'System Default', 'Inherit' ].includes( newValue.family ) ) {
								const weight = newValue?.weight ?? '400';
								font += 'family=' + newValue.family + ':' + weight;
							}
						}
						break;
					case 'vite-border':
						if ( newValue?.color?.hover ) {
							css.desktop += makeCSS( selectors.map( s => s + ':hover' ), [ 'border-color' ], newValue.color.hover );
						}
						css.desktop += makeCSS( selectors, [], borderCSS( newValue ) );
						break;
					default:
						if ( isObject( newValue ) ) {
							if ( ( Object.keys( newValue ) ).some( v => DEVICES.includes( v ) ) ) {
								for ( const device of DEVICES ) {
									if ( value?.[ device ] ) {
										css[ device ] += makeCSS( selectors, properties, ( newValue[ device ] ).toString() );
									}
								}
							}

							if ( ( Object.keys( newValue ) ).some( k => STATES.includes( k ) ) ) {
								for ( const [ k, v ] of Object.entries( newValue ) ) {
									css.desktop += makeCSS( selectors.map( s => {
										if ( k.startsWith( 'normal' ) || STATES.includes( v ) ) {
											return s;
										}
										return `${ s }:${ k }`;
									} ), properties, v );
								}
							} else {
								for ( const [ k, v ] of Object.entries( newValue ) ) {
									css.desktop += makeCSS(
										selectors,
										properties.map( s => {
											return `${ s }${ k }`;
										} ),
										v
									);
								}
							}
						} else {
							css.desktop += makeCSS( selectors, properties, ( newValue ).toString() );
						}
				}

				id = id.replace( /[\[\]']+/g, '-' );
				const $head = $( 'head' );

				if ( font.length !== 0 ) {
					$( `link#${ id }-font` ).remove();
					$head.append( `<link id="${ id }-font" href="https://fonts.googleapis.com/css?${ font }" rel="stylesheet">` );
				}

				$( `style#${ id }` ).remove();

				const $style = $( `<style id="${ id }">${ css.desktop }</style>` );

				if ( css.tablet ) {
					$style.append( `@media (max-width: 721px){ ${ css.tablet } }` );
				}

				if ( css.mobile ) {
					$style.append( `@media (max-width: 321px){ ${ css.mobile } }` );
				}

				$head.append( $style );
			} );
		} );
	}
}


import $ from 'jquery';
import * as controls from './controls';
import './customizer.scss';
import { reset } from './utils';

const api = wp.customize;

{
	api.bind( 'ready', () => {
		let values = {};
		const toStyles = val => {
			let style = ':root{';
			for ( const key in val ) {
				style += `${ key }:${ val[ key ] };`;
			}
			style += '}';
			$( 'style#vite-root-css' ).remove();
			$( 'head' ).append( `<style id="vite-root-css">${ style }</style>` );
		};
		[ 'global-palette', 'link-colors' ].forEach( control => {
			const val = api( `vite[${ control }]` ).get();
			if ( val ) {
				values = { ...values, ...val };
				toStyles( values );
			}
			api( `vite[${ control }]`, value => {
				value.bind( to => {
					values = { ...values, ...to };
					toStyles( values );
				} );
			} );
		} );

		const getControl = ( id ) => api( id );

		const analyzeConditions = ( conditions = {} ) => {
			const relation = conditions.relation ?? 'AND';
			const terms = conditions.terms ?? [];

			if ( ! terms.length ) return true;

			const results = terms.map( term => {
				const { name, operator = '===', value } = term;
				const control = getControl( name );
				if ( ! control ) return true;
				const setting = control.get();
				switch ( operator.trim() ) {
					case '===':
						return setting === value;
					case '!==':
						return setting !== value;
					case '==':
						return setting == value; // eslint-disable-line
					case '!=':
						return setting != value; // eslint-disable-line
					case '>':
						return setting > value;
					case '>=':
						return setting >= value;
					case '<':
						return setting < value;
					case '<=':
						return setting <= value;
					case 'contains':
						return setting.includes( value );
					case '!contains':
						return ! setting.includes( value );
					case 'in':
						return value.includes( setting );
					case '!in':
						return ! value.includes( setting );
					default:
						return false;
				}
			} );

			if ( relation === 'AND' ) {
				return results.every( result => result );
			}

			if ( relation === 'OR' ) {
				return results.some( result => result );
			}

			return true;
		};

		const analyzeCondition = ( condition = {} ) => {
			const results = Object.entries( condition ).map( ( [ key, value ] ) => {
				const control = getControl( key.replace( '!', '' ) );
				if ( ! control ) return true;
				const setting = control.get();
				if ( key.startsWith( '!' ) || key.endsWith( '!' ) ) {
					if ( Array.isArray( value ) ) {
						return ! value.includes( setting );
					}
					return setting !== value;
				}
				if ( Array.isArray( value ) ) {
					return value.includes( setting );
				}
				return setting === value;
			} );

			return results.every( result => result );
		};

		for ( const c of [ 'conditions', 'condition' ] ) {
			if ( window._VITE_CUSTOMIZER_?.[ c ] ) {
				for ( const id in window._VITE_CUSTOMIZER_[ c ] ) {
					const init = ( element ) => {
						const control = getControl( id );
						if ( ! control ) return;
						const state = _VITE_CUSTOMIZER_[ c ][ id ];
						const result = () => c === 'conditions' ? analyzeConditions( state ) : analyzeCondition( state );
						const setActiveState = () => {
							element.active.set( result() );

							if ( ! result() ) {
								element.container.addClass( 'vite-hidden-control' );
							} else {
								element.container.removeClass( 'vite-hidden-control' );
							}
						};
						control.bind( setActiveState );
						element.active.validate = result;
						for ( const i in state ) {
							if ( 'terms' in state ) {
								for ( const term of state.terms ) {
									const setting = getControl( term.name );
									if ( setting ) {
										setting.bind( setActiveState );
									}
								}
							} else {
								const setting = getControl( i.replace( '!', '' ) );
								if ( setting ) {
									setting.bind( setActiveState );
								}
							}
						}
						setActiveState();
					};

					api.control( id, init );
				}
			}
		}

		const initBuilder = ( builder ) => {
			api.panel( `vite[${ builder }-builder]`, panel => {
				const builderSection = api.section( `vite[${ builder }-builder]` );

				if ( ! builderSection ) return;
				const settings = api.section( `vite[${ builder }-builder-settings]` );

				panel.expanded.bind( expanded => {
					_.each( builderSection.controls(), control => {
						if ( 'resolved' === control.deferred.embedded.state() ) return;
						control.renderContent();
						control.deferred.embedded.resolve();
						control.container.trigger( 'init' );
					} );

					if ( expanded ) {
						$( '.wp-full-overlay' ).attr( `data-${ builder }-builder`, 'active' );
					} else {
						$( '.wp-full-overlay' ).removeAttr( `data-${ builder }-builder` );
					}

					if ( settings ) {
						_.each( settings.controls(), control => {
							if ( 'resolved' === control.deferred.embedded.state() ) return;
							control.renderContent();
							control.deferred.embedded.resolve();
							control.container.trigger( 'init' );
						} );
					}
				} );
			} );
		};

		for ( const builder of [ 'header', 'footer' ] ) {
			initBuilder( builder );
		}
	} );
}

for ( const control in controls ) {
	controls[ control ]();
}

reset();

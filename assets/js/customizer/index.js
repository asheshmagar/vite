import $ from 'jquery';
import * as controls from './controls';
import './customizer.scss';

const api = wp.customize;

{
	api.bind( 'preview-ready', () => {
		api.selectiveRefresh.bind( 'render-partials-response', res => {
			if ( ! res?.viteDynamicCSS ) return;
			const $style = $( '#vite-dynamic-css' );
			$style.html( res.viteDynamicCSS );
		} );
	} );
}

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

		api.state.create( 'vite-tab' );
		api.state( 'vite-tab' ).set( 'general' );
		$( '#customize-theme-controls' ).on( 'click', '.customize-section-back', () => api.state( 'vite-tab' ).set( 'general' ) );

		const getControl = ( id ) => {
			if ( 'vite-tab' === id ) {
				return api.state( 'vite-tab' );
			}
			return api( id );
		};

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
					return setting !== value;
				}
				return setting === value;
			} );

			return results.every( result => result );
		};

		[ 'conditions', 'condition' ].forEach( c => {
			if ( window._VITE_CUSTOMIZER_?.[ c ] ) {
				for ( const id in window._VITE_CUSTOMIZER_[ c ] ) {
					const init = ( element ) => {
						const control = getControl( id );
						if ( ! control ) return;
						const state = _VITE_CUSTOMIZER_[ c ][ id ];
						const result = () => c === 'conditions' ? analyzeConditions( state ) : analyzeCondition( state );
						const setActiveState = () => {
							element.active.set( result() );
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
		} );

		const initHeaderBuilderPanel = ( panel ) => {
			const builderSection = api.section( 'vite[header-builder]' );

			if ( ! builderSection ) return;
			const settings = api.section( 'vite[header-builder-settings]' );

			panel.expanded.bind( expanded => {
				_.each( builderSection.controls(), control => {
					if ( 'resolved' === control.deferred.embedded.state() ) return;
					control.renderContent();
					control.deferred.embedded.resolve();
					control.container.trigger( 'init' );
				} );

				if ( expanded ) {
					$( '.wp-full-overlay' ).attr( 'data-builder', 'active' );
				} else {
					$( '.wp-full-overlay' ).removeAttr( 'data-builder' );
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
		};

		api.panel( 'vite[header-builder]', initHeaderBuilderPanel );
	} );
}

for ( const control in controls ) {
	controls[ control ]();
}


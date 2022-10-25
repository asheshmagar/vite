import $ from 'jquery';
import _ from 'lodash';
import * as controls from './controls';
import './customizer.scss';

const api = wp.customize;

/**
 * Extend sections and panels.
 */
{
	api.bind( 'pane-contents-reflowed', () => {
		const panels = [],
			sections = [];

		api.section.each( section => {
			if ( 'vite-section' === section.params.type && !! section.params?.section ) {
				sections.push( section );
			}
		} );

		sections.sort( api.utils.prioritySort ).reverse();

		$.each( sections, ( i, section ) => {
			const parentContainer = $( `#sub-accordion-section-${ section.params.section }` );
			parentContainer.children( '.section-meta' ).after( section.headContainer );
		} );

		api.panel.each( panel => {
			if ( 'vite-panel' === panel.params.type && !! panel.params?.panel ) {
				panels.push( panel );
			}
		} );

		panels.sort( api.utils.prioritySort ).reverse();

		$.each( panels, ( i, panel ) => {
			const parentContainer = $( `#sub-accordion-panel-${ panel.params.panel }` );
			parentContainer.children( '.section-meta' ).after( panel.headContainer );
		} );
	} );

	const panelEmbed = api.Panel.prototype.embed,
		panelIsContextuallyActive = api.Panel.prototype.isContextuallyActive,
		panelAttachEvents = api.Panel.prototype.attachEvents;

	api.Panel = api.Panel.extend( {
		attachEvents() {
			if ( 'vite-panel' !== this.params.type || ! this.params?.panel ) {
				panelAttachEvents.call( this );
				return;
			}
			panelAttachEvents.call( this );

			const panel = this;

			panel.expanded.bind( expanded => {
				const parent = api.panel( panel.params.panel );

				if ( expanded ) {
					parent.contentContainer.addClass( 'current-panel-parent' );
				} else {
					parent.contentContainer.removeClass( 'current-panel-parent' );
				}
			} );

			panel.container
				.find( '.customize-panel-back' )
				.off( 'click keydown' )
				.on( 'click keydown', evt => {
					if ( api.utils.isKeydownButNotEnterEvent( evt ) ) {
						return;
					}
					evt.preventDefault();
					if ( panel.expanded() ) {
						api.panel( panel.params.panel ).expand();
					}
				} );
		},
		embed() {
			if ( 'vite-panel' !== this.params.type || ! this.params?.section ) {
				panelEmbed.call( this );
				return;
			}

			panelEmbed.call( this );

			const panel = this;
			const parentContainer = $( `#sub-accordion-panel-${ panel.params.panel }` );

			parentContainer.append( panel.headContainer );
		},
		isContextuallyActive() {
			if ( 'vite-panel' !== this.params.type ) {
				return panelIsContextuallyActive.call( this );
			}

			const panel = this;
			const children = this._children( 'panel', 'section' );
			let activeCount = 0;

			api.panel.each( child => {
				if ( child.params?.panel !== panel.id ) return;
				children.push( child );
			} );

			children.sort( api.utils.prioritySort );

			_( children ).each( child => {
				if ( child.active() && child.isContextuallyActive() ) {
					activeCount += 1;
				}
			} );

			return 0 !== activeCount;
		},
	} );

	const sectionEmbed = api.Section.prototype.embed,
		sectionIsContextuallyActive = api.Section.prototype.isContextuallyActive,
		sectionAttachEvents = api.Section.prototype.attachEvents;

	api.Section = api.Section.extend( {
		attachEvents() {
			const section = this;

			if ( 'vite-section' !== section.params.type || ! section.params?.section ) {
				sectionAttachEvents.call( this );
				return;
			}
			sectionAttachEvents.call( this );

			section.expanded.bind( expanded => {
				const parent = api.section( section.params.section );

				if ( expanded ) {
					parent.contentContainer.addClass( 'current-section-parent' );
				} else {
					parent.contentContainer.removeClass( 'current-section-parent' );
				}
			} );

			section.container
				.find( '.customize-section-back' )
				.off( 'click keydown' )
				.on( 'click keydown', evt => {
					if ( api.utils.isKeydownButNotEnterEvent( evt ) ) {
						return;
					}
					evt.preventDefault();
					if ( section.expanded() ) {
						api.section( section.params.section ).expand();
					}
				} );
		},
		embed() {
			if ( 'vite-section' !== this.params.type ) {
				sectionEmbed.call( this );
				return;
			}

			sectionEmbed.call( this );

			const section = this;
			const parentContainer = $( `#sub-accordion-section-${ section.params.section }` );

			parentContainer.append( section.headContainer );
		},
		isContextuallyActive() {
			if ( 'vite-section' !== this.params.type || ! this.params?.section ) {
				return sectionIsContextuallyActive.call( this );
			}

			const section = this;
			const children = this._children( 'section', 'control' );
			let activeCount = 0;

			api.section.each( child => {
				if ( child.params?.section !== section.id ) return;
				children.push( child );
			} );

			children.sort( api.utils.prioritySort );

			_( children ).each( child => {
				if ( child?.isContextuallyActive ) {
					if ( child.active() && child.isContextuallyActive() ) {
						activeCount += 1;
					}
				} else {
					if ( child.active() ) { // eslint-disable-line no-lonely-if
						activeCount += 1;
					}
				}
			} );

			return 0 !== activeCount;
		},
	} );
}

// Initialize builder and its panel and section.
{
	api.bind( 'ready', () => {
		const builderPanelSection = {};

		api.control.each( control => {
			if ( control.section() ) {
				const section = api.section( control.section() );
				if ( section?.panel() && 'vite-builder-section' === section.params?.type ) {
					const panel = api.panel( section.panel() );
					if ( panel?.id && 'vite-builder-panel' === panel.params?.type ) {
						builderPanelSection[ panel.id ] = [
							...( builderPanelSection?.[ panel.id ] || [] ),
							section.id,
						];
					}
				}
			}
		} );

		const initBuilder = ( panelId, sectionIds ) => {
			const panel = api.panel( panelId );

			if ( ! panel || ! sectionIds?.length ) return;

			sectionIds = _.uniq( sectionIds );

			const selectors = [];

			for ( const sectionId of sectionIds ) {
				const section = api.section( sectionId );

				if ( section ) {
					const contentContainer = section.contentContainer;
					const headContainer = section.headContainer;

					selectors.push( `[id="sub-accordion-section-${ section.id }"]` );
					headContainer.attr( 'data-navigator-hidden', 'true' );
					contentContainer.find( '.section-meta' ).addClass( 'hidden' ).hide();

					panel.expanded.bind( ( isExpanded ) => {
						if ( ! section.controls()?.length ) return;

						_.each( section.controls(), control => {
							if ( 'resolved' === control.deferred.embedded.state() ) return;
							control.renderContent();
							control.deferred.embedded.resolve();
							control.container.trigger( 'init' );
						} );

						if ( isExpanded ) {
							$( `#sub-accordion-panel-${ panelId } li.control-section` ).attr( 'data-navigator-hidden', 'true' );
							$( 'body' ).attr( 'data-builder', 'active' );
						} else {
							$( 'body' ).removeAttr( 'data-builder' );
						}
					} );
				}
			}
			let styles = '';
			if ( selectors?.length > 0 ) {
				for ( const selector of selectors ) {
					styles += `[data-builder="active"] .in-sub-panel:not(.section-open) ul${ selector }{transform:none;height:auto;visibility:visible;top: 75px;}`;
				}
			}
			$( 'head' ).append( `<style>${ styles }</style>` );
		};

		if ( ! _.isEmpty( builderPanelSection ) ) {
			for ( const panel in builderPanelSection ) {
				if ( panel && builderPanelSection?.[ panel ] ) {
					initBuilder( panel, builderPanelSection[ panel ] || [] );
				}
			}
		}
	} );
}

{
	api.bind( 'ready', () => {
		api.state.create( 'viteTab' );
		api.state( 'viteTab' ).set( 'general' );

		const $controls = $( '#customize-theme-controls' );

		const focusSection = ( sectionId ) => {
			const section = api.section( sectionId );
			if ( section ) {
				const container = section.contentContainer[ 0 ];
				container.addClass( 'vite-prevent-transition' );
				setTimeout( () => {
					section.focus();
				}, 10 );
				setTimeout( () => {
					container.removeClass( 'vite-prevent-transition' ).removeClass( 'busy' );
					container.css( 'top', '' );
				}, 300 );
			}
		};

		$controls.on( 'click', '.vite-tab', function( e ) {
			e.preventDefault();
			const target = $( this ).attr( 'data-target' );
			api.state( 'viteTab' ).set( $( this ).attr( 'data-tab' ) );
			if ( target ) {
				focusSection( target );
			}
		} );

		api.state( 'viteTab' ).bind( () => {
			const tab = api.state( 'viteTab' ).get();
			$( '.vite-tab' ).removeClass( 'active' ).filter( `.vite-${ tab }-tab` ).addClass( 'active' );
		} );

		$controls.on( 'click', '.customize-section-back', () => api.state( 'viteTab' ).set( 'general' ) );
	} );
}

{
	api.bind( 'preview-ready', () => {
		api.selectiveRefresh.bind( 'render-partials-response', res => {
			if ( ! res?.viteDynamicCSS ) return;
			const $style = $( '#vite-dynamic-css' );
			$style.html( res.viteDynamicCSS );
		} );
	} );
}

for ( const control in controls ) {
	controls[ control ]();
}


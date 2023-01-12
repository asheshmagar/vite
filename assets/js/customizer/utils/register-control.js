import $ from 'jquery';
import { render } from '@wordpress/element';

const api = wp.customize;

const registerControl = ( type, Component ) => {
	( api.controlConstructor[ type ] = api.Control.extend( {
		initialize( id, params ) {
			const control = this,
				args = params || {};

			args.params = args?.params || {};
			args.params.type = args.params?.type ? args.params.type : 'vite';

			api.Control.prototype.initialize.call( control, id, params );
			control.container[ 0 ].classList.remove( 'customize-control' );

			if ( ! args.params?.content ) {
				args.params.content = $( '<li></li>', {
					id: `customize-control-${ id.replace( /]/g, '' ).replace( /\[/g, '-' ) }`,
					class: `customize-control customize-control-${ args.params.type }`,
				} );
			}

			if ( args.params?.inputAttrs?.separator ) {
				args.params.content = args.params.content.replace( 'class=', 'data-separator class=' );
			}

			api.Control.prototype.initialize.call( control, id, args );
		},
		ready() {
			const control = this;
			api.Control.prototype.ready.call( control );
			control.deferred.embedded.done();
		},
		embed() {
			const control = this,
				section = control.section();

			if ( ! section ) return;

			api.section( section, sec => {
				if ( sec.expanded() || api.settings.autofocus.control === control.id ) {
					control.actuallyEmbed();
				} else {
					sec.expanded.bind( expanded => {
						if ( expanded ) control.actuallyEmbed();
					} );
				}
			} );
		},
		actuallyEmbed() {
			const control = this;
			if ( 'resolved' === control.deferred.embedded.state() ) return;
			control.renderContent();
			control.deferred.embedded.resolve();
			control.container.trigger( 'init' );
		},
		focus( args ) {
			const control = this;
			control.actuallyEmbed();
			api.Control.prototype.focus.call( control, args );
		},
		renderContent() {
			const control = this;
			render(
				<Component control={ control } customizer={ wp.customize } />,
				control.container[ 0 ]
			);
		},
	} ) );
};

export default registerControl;

import $ from 'jquery';
import { render } from '@wordpress/element';

const registerControl = ( type, Component ) => {
	( wp.customize.controlConstructor[ type ] = wp.customize.Control.extend( {
		initialize( id, params ) {
			const control = this,
				args = params || {};

			args.params = args?.params || {};
			args.params.type = args.params?.type ? args.params.type : 'vite';

			wp.customize.Control.prototype.initialize.call( control, id, params );
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

			wp.customize.Control.prototype.initialize.call( control, id, args );
		},
		ready() {
			const control = this;
			wp.customize.Control.prototype.ready.call( control );
			control.deferred.embedded.done();
		},
		embed() {
			const control = this,
				section = control.section();

			if ( ! section ) return;

			wp.customize.section( section, sec => {
				if ( sec.expanded() || wp.customize.settings.autofocus.control === control.id ) {
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
			wp.customize.Control.prototype.focus.call( control, args );
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

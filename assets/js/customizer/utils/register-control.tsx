import $ from 'jquery';
import { render } from '@wordpress/element';
import { ControlPropsType } from '../controls/types';

const api = wp.customize;

const registerControl = ( type: string, Component: React.FC<ControlPropsType> ) => {
	( api.controlConstructor[ type ] = api.Control.extend( {
		initialize( id: string, params: any ) {
			const args = params || {};

			args.params = args?.params || {};
			args.params.type = args.params?.type ? args.params.type : 'vite';

			api.Control.prototype.initialize.call( this, id, params );
			this.container[ 0 ].classList.remove( 'customize-control' );

			if ( ! args.params?.content ) {
				args.params.content = $( '<li></li>', {
					id: `customize-control-${ id.replace( /]/g, '' ).replace( /\[/g, '-' ) }`,
					class: `customize-control customize-control-${ args.params.type }`,
				} );
			}

			if ( args.params?.inputAttrs?.separator ) {
				args.params.content = args.params.content.replace( 'class=', 'data-separator class=' );
			}

			api.Control.prototype.initialize.call( this, id, args );
		},
		ready() {
			api.Control.prototype.ready.call( this );
			this.deferred.embedded.done();
		},
		embed() {
			const section = this.section();

			if ( ! section ) return;

			api.section( section, ( sec: any ) => {
				if ( sec.expanded() || api.settings.autofocus.control === this.id ) {
					this.actuallyEmbed();
				} else {
					sec.expanded.bind( ( expanded: any ) => {
						if ( expanded ) this.actuallyEmbed();
					} );
				}
			} );
		},
		actuallyEmbed() {
			if ( 'resolved' === this.deferred.embedded.state() ) return;
			this.renderContent();
			this.deferred.embedded.resolve();
			this.container.trigger( 'init' );
		},
		focus( args: any ) {
			this.actuallyEmbed();
			api.Control.prototype.focus.call( this, args );
		},
		renderContent() {
			render(
				<Component control={ this } customizer={ api } />,
				this.container[ 0 ]
			);
		},
	} ) );
};

export default registerControl;

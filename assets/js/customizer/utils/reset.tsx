import { render, useState } from '@wordpress/element';
// @ts-ignore
import { Button, __experimentalConfirmDialog as ConfirmDialog } from '@wordpress/components';
import $ from 'jquery';
import { __ } from '@wordpress/i18n';

const api = wp.customize;

const ResetButton: React.FC<any> = () => {
	const [ state, setState ] = useState( {
		open: false,
		resetting: false,
	} );

	const reset = ( e: React.SyntheticEvent ) => {
		$.ajax( {
			url: _VITE_CUSTOMIZER_.ajaxURL,
			data: {
				wp_customize: 'on',
				action: 'vite-reset-customizer',
				nonce: _VITE_CUSTOMIZER_.resetNonce,
			},
			method: 'POST',
			beforeSend() {
				$( e.target ).addClass( 'is-busy' );
				setState( prev => ( {
					...prev,
					resetting: true,
				} ) );
			},
			complete() {
				api.state( 'saved' ).set( true );
				$( e.target ).removeClass( 'is-busy' );
				setState( {
					resetting: false,
					open: false,
				} );
			},
		} );
	};
	return (
		<>
			<Button isSecondary disabled={ state.resetting } isBusy={ state.resetting } onClick={ ( e: React.SyntheticEvent ) => {
				e.preventDefault();
				setState( prev => ( {
					...prev,
					open: true,
				} ) );
			} }>
				Reset
			</Button>
			<ConfirmDialog
				title={ __( 'Reset Settings', 'vite' ) }
				confirmButtonText={ __( 'Reset', 'vite' ) }
				isOpen={ state.open }
				onConfirm={ reset }
				onCancel={ () => setState( prev => ( {
					...prev,
					open: false,
				} ) ) }
			>
				{ __( 'Are you sure you want to reset all settings? This will reset all settings to default values.', 'vite' ) }
			</ConfirmDialog>
		</>
	);
};

export default () => {
	api.bind( 'ready', () => {
		const $actions = $( '#customize-header-actions' );

		$actions.append( $( '<div id="vite-reset" style="display: inline-block; float: right;"></div>' ) );

		const root = document.getElementById( 'vite-reset' );

		if ( root ) {
			render( <ResetButton />, root );
		}
	} );
};

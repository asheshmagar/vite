import { render, useState } from '@wordpress/element';
import { Button, __experimentalConfirmDialog as ConfirmDialog } from '@wordpress/components';
import $ from 'jquery';
import { __ } from '@wordpress/i18n';

const api = wp.customize;

const ResetButton = () => {
	const [ isOpen, setOpen ] = useState( false );
	const [ isResetting, setIsResetting ] = useState( false );

	const reset = ( e ) => {
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
				setIsResetting( true );
			},
			complete() {
				api.state( 'saved' ).set( true );
				window.location.reload();
				$( e.target ).removeClass( 'is-busy' );
				setOpen( false );
				setIsResetting( true );
			},
		} );
	};
	return (
		<>
			<Button isSecondary isBusy={ isResetting } onClick={ e => {
				e.preventDefault();
				setOpen( true );
			} }>
				Reset
			</Button>
			<ConfirmDialog
				title={ __( 'Reset Settings', 'vite' ) }
				confirmButtonText={ __( 'Reset', 'vite' ) }
				isOpen={ isOpen }
				onConfirm={ reset }
				onCancel={ () => setOpen( false ) }
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

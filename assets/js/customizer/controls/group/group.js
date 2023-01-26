import { memo, useState } from '@wordpress/element';
import { Button } from '@wordpress/components';
import SubControl from './sub-control';
import { __ } from '@wordpress/i18n';
import { isEmpty } from 'lodash';
import $ from 'jquery';

export default memo( props => {
	const {
		control: {
			params: {
				label = __( 'Group', 'vite' ),
				inputAttrs: {
					sub_controls: subControls = {},
					initialOpen = false,
				},
			},
		},
		customizer,
	} = props;
	const [ body, setBody ] = useState( null );
	const [ open, setOpen ] = useState( initialOpen );

	if ( isEmpty( subControls ) ) return null;

	return (
		<>
			<div className="vite-control vite-group-control">
				<div className="vite-control-head">
					<Button
						onClick={ () => {
							if ( body ) {
								$( body ).slideToggle( 300 );
							}
							setOpen( prev => ! prev );
						} }
						icon={ open ? 'arrow-up' : 'arrow-down' }
						iconPosition="right"
						text={ label }
					/>
				</div>
				<div ref={ setBody } className="vite-control-body" style={ { display: initialOpen ? undefined : 'none' } }>
					{ Object.entries( subControls ).map( ( [ id, params ] ) => (
						<SubControl key={ id } { ...{
							id,
							customizer,
							...params,
						} } />
					) ) }
				</div>
			</div>
		</>
	);
} );

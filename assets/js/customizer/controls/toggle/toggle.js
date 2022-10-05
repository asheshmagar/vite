import { ToggleControl } from '@wordpress/components';
import { useState, memo } from '@wordpress/element';
import { Tooltip } from '../../components';

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );
	return (
		<div className="vite-control vite-toggle-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{
						description && (
							<Tooltip>
								<span className="customize-control-description">{ description }</span>
							</Tooltip>
						)
					}
				</div>
			) }
			<ToggleControl
				checked={ !! value }
				onChange={ () => {
					setValue( ! value );
					setting.set( ! value );
				} }
			/>
		</div>
	);
} );

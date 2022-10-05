import { memo, useState } from '@wordpress/element';
import { Tooltip, CustomindColorPicker } from '../../components';

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				type,
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	return (
		<div className="vite-control vite-color-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{ description && (
						<Tooltip>
							<span className="customize-control-description">{ description }</span>
						</Tooltip>
					) }
				</div>
			) }
			<div className="vite-control-body">
				<CustomindColorPicker type={ type } value={ value } onChange={ ( color ) => {
					setValue( color );
					setting.set( color );
				} } />
			</div>
		</div>
	);
} );

import { memo, useState } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';
import { Tooltip } from '../../components';

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				inputAttrs: {
					dropdown = {},
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	const options = Object.entries( dropdown || {} )?.map( ( [ id, name ] ) => (
		{ label: name, value: id }
	) ) || [];

	return (
		<div className="vite-control vite-dropdown-categories-control">
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
			<div className="vite-control-body">
				{ !! options.length && (
					<SelectControl
						value={ options.find( o => o.id === value )?.value || '' }
						onChange={ val => {
							setValue( val );
							setting.set( val );
						} }
						options={ options } />
				) }
			</div>
		</div>
	);
} );

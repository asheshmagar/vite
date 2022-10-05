import { memo, useState } from '@wordpress/element';
import { useDeviceSelector } from '../../hooks';
import { Tooltip } from '../../components';
import {
	__experimentalBoxControl as BoxControl,
	__experimentalUseCustomUnits as useCustomUnits,
} from '@wordpress/components';
import { DEVICES } from '../../constants';

export default memo( ( props ) => {
	let {
		control: {
			setting,
			params: {
				label,
				description,
				inputAttrs: {
					units = [ 'px', 'em', 'rem', '%' ],
					responsive = false,
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() ?? {} );
	const { device, DeviceSelector } = useDeviceSelector();

	units = useCustomUnits( { availableUnits: units } );

	return (
		<div className="vite-control vite-dimensions-control">
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
					{ responsive && <DeviceSelector /> }
				</div>
			) }
			<div className="vite-control-body">
				{ responsive ? (
					DEVICES.map( ( d ) => (
						<div key={ d } style={ { display: d === device ? 'block' : 'none' } }>
							<BoxControl
								label=""
								values={ value?.[ d ] ?? {} }
								onChange={ ( val ) => {
									const temp = { ...value };
									temp[ d ] = val;
									setValue( temp );
									setting.set( temp );
								} }
								units={ units }
							/>
						</div>
					) )
				) : (
					<BoxControl
						label=""
						values={ value ?? {} }
						onChange={ ( val ) => {
							setValue( val );
							setting.set( val );
						} }
						units={ units }
					/>
				) }
			</div>
		</div>
	);
} );

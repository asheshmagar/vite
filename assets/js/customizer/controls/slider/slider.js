import { useState, memo, RawHTML } from '@wordpress/element';
import { CustomindRange, Tooltip } from '../../components';
import { useDeviceSelector } from '../../hooks';
import { DEVICES } from '../../constants';

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				inputAttrs: {
					responsive = false,
					units = [],
					noUnits = true,
					min = 0,
					max = 300,
					step = 1,
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	const { device, DeviceSelector } = useDeviceSelector();

	return (
		<div className="vite-control vite-slider-control" >
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{ responsive && <DeviceSelector /> }
				</div>
			) }
			<div className="vite-control-body">
				{ responsive ? (
					DEVICES.map( ( d ) => (
						device === d && (
							<CustomindRange
								key={ d }
								value={ value?.[ d ] ?? '' }
								onChange={ val => {
									const temp = { ...value };
									temp[ d ] = val;
									setValue( temp );
									setting.set( temp );
								} }
								defaultUnit="px"
								units={ units }
								noUnits={ noUnits }
								min={ min }
								max={ max }
								step={ step }
							/>
						)
					) )
				) : (
					<CustomindRange
						value={ value ?? '' }
						onChange={ val => {
							setValue( val );
							setting.set( val );
						} }
						defaultUnit="px"
						units={ units }
						noUnits={ noUnits }
						min={ min }
						max={ max }
						step={ step }
					/>
				) }
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

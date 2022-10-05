import { useState, memo } from '@wordpress/element';
import { RangeControl, __experimentalUseCustomUnits as useCustomUnits, __experimentalUnitControl as UnitControl } from '@wordpress/components';
import { Tooltip } from '../../components';
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
					...inputAttrs
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() ?? '' );

	let customUnits = useCustomUnits( { availableUnits: units } );

	if ( units.length === 0 ) {
		customUnits = false;
	}

	const attrs = {
		min: 0, max: 500, step: 1,
		...( inputAttrs || {} ),
	};

	const { device, DeviceSelector } = useDeviceSelector();

	return (
		<div className="vite-control vite-slider-control" >
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
						<div style={ { display: d === device ? 'block' : 'none' } } key={ d } className="vite-control-body-inner">
							<RangeControl
								value={ 'string' === typeof value[ d ] ? parseFloat( value.replace( /\D/g, '' ) ) : value[ d ] ?? '' }
								onChange={ val => {
									const temp = { ...value };
									if ( 'string' === typeof value ) {
										val = val + value.replace( /\d/g, '' );
									}

									temp[ d ] = val;

									setValue( temp );
									setting.set( temp );
								} }
								{ ...attrs }
								withInputField={ false }
							/>
							<UnitControl
								value={ value }
								onChange={ val => {
									const temp = { ...value };
									if ( ! customUnits ) {
										val = parseFloat( val.replace( 'px', '' ) );
									}
									temp[ d ] = val;
									setValue( val );
									setting.set( val );
								} }
								units={ customUnits }
								disableUnits={ ! customUnits }
								{ ...attrs }
							/>
						</div>
					) )
				) : (
					<div className="vite-control-body-inner">
						<RangeControl
							value={ 'string' === typeof value ? parseFloat( value.replace( /\D/g, '' ) ) : value ?? '' }
							onChange={ val => {
								if ( 'string' === typeof value ) {
									val = val + value.replace( /\d/g, '' );
								}

								setValue( val );
								setting.set( val );
							} }
							{ ...attrs }
							withInputField={ false }
						/>
						<UnitControl
							value={ value }
							onChange={ val => {
								if ( ! customUnits ) {
									val = parseFloat( val.replace( 'px', '' ) );
								}
								setValue( val );
								setting.set( val );
							} }
							units={ customUnits }
							disableUnits={ ! customUnits }
							{ ...attrs }
						/>
					</div>
				) }
			</div>
		</div>
	);
} );

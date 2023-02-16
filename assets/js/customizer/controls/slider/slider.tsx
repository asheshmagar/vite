import { useState, memo, RawHTML } from '@wordpress/element';
import { ViteRange } from '../../components';
import { useDeviceSelector } from '../../hooks';
import { DEVICES } from '../../constants';
import { isEqual } from 'lodash';
import { Button } from '@wordpress/components';
import { ControlPropsType } from '../types';

const Slider: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				inputAttrs: {
					responsive = false,
					units = [],
					min = 0,
					max = 300,
					step = 1,
					allow_reset: allowReset = true,
					marks = false,
					input = true,
				},
				default: defaultValue,
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
					{ ( ! isEqual( defaultValue, value ) && allowReset ) && (
						<Button
							onClick={ () => {
								setValue( defaultValue );
								setting.set( defaultValue );
							} }
							icon="image-rotate"
							className="vite-reset"
						/>
					) }
					{ responsive && <DeviceSelector /> }
				</div>
			) }
			<div className="vite-control-body">
				{ responsive ? (
					DEVICES.map( ( d ) => (
						device === d && (
							<ViteRange
								key={ d }
								value={ value?.[ d ] ?? ( value?.desktop ?? {} ) }
								onChange={ val => {
									const temp = { ...value };
									temp[ d ] = val;
									setValue( temp );
									setting.set( temp );
								} }
								defaultUnit="px"
								units={ units }
								min={ min }
								max={ max }
								step={ step }
								marks={ marks }
								input={ input }
							/>
						)
					) )
				) : (
					<ViteRange
						value={ value ?? '' }
						onChange={ val => {
							setValue( val );
							setting.set( val );
						} }
						defaultUnit="px"
						units={ units }
						min={ min }
						max={ max }
						step={ step }
						marks={ marks }
						input={ input }
					/>
				) }
			</div>
			{ description && (
				<RawHTML className="customize-control-description">{ description }</RawHTML>
			) }
		</div>
	);
};

export default memo( Slider );

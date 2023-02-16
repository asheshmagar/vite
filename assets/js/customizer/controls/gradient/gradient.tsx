import { memo, useState, RawHTML } from '@wordpress/element';
import { ViteColorPicker } from '../../components';
import { isEqual } from 'lodash';
import { Button } from '@wordpress/components';
import { ControlPropsType } from '../types';

const Gradient: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				default: defaultValue,
				inputAttrs: {
					allow_reset: allowReset = true,
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	return (
		<>
			<div className="vite-control vite-gradient-control">
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
					</div>
				) }
				<div className="vite-control-body">
					<div className="vite-gradient-picker">
						<ViteColorPicker
							value={ value }
							onChange={ val => {
								setting.set( val );
								setValue( val );
							} }
							type="gradient"
						/>
					</div>
				</div>
				{ description && (
					<RawHTML className="customize-control-description">{ description }</RawHTML>
				) }
			</div>
		</>
	);
};

export default memo( Gradient );

import { ToggleControl, Button } from '@wordpress/components';
import { useState, memo, RawHTML } from '@wordpress/element';
import { isEqual } from 'lodash';
import { ControlPropsType } from '../types';

const Toggle: React.FC<ControlPropsType> = ( props ) => {
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
		<div className="vite-control vite-toggle-control">
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
				<ToggleControl
					checked={ !! value }
					onChange={ () => {
						setValue( ! value );
						setting.set( ! value );
					} }
				/>
			</div>
			{ description && (
				<RawHTML className="customize-control-description">{ description }</RawHTML>
			) }
		</div>
	);
};

export default memo( Toggle );

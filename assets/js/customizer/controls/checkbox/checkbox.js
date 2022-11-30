import { useState, memo, RawHTML } from '@wordpress/element';
import { CheckboxControl, Button } from '@wordpress/components';
import { isEqual } from 'lodash';

export default memo( ( props ) => {
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
		<div className="vite-control vite-checkbox-control">
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
				<CheckboxControl
					checked={ !! value }
					onChange={ () => {
						setValue( ! value );
						setting.set( ! value );
					} }
				/>
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

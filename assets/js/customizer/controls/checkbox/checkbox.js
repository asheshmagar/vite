import { useState, memo, RawHTML } from '@wordpress/element';
import { CheckboxControl } from '@wordpress/components';

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
		<div className="vite-control vite-checkbox-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
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
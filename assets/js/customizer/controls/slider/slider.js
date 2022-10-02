import { useState, memo } from '@wordpress/element';
import { RangeControl } from '@wordpress/components';

export default memo( ( props ) => {
	const [ value, setValue ] = useState( props.control.setting.get() );
	const {
		label,
		input_attrs: inputAttrs,
	} = props.control.params;

	const attrs = {
		min: 0, max: 500, step: 1,
		...( inputAttrs || {} ),
	};
	return (
		<div className="customind-control customind-slider-control" >
			{ label && (
				<div className="customize-control-title-wrap">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<RangeControl
				value={ value }
				onChange={ val => {
					setValue( val );
					props.control.setting.set( val );
				} }
				resetFallbackValue={ props.control.params.default || '' }
				{ ...attrs }
			/>
		</div>
	);
} );

import { RangeControl } from '@wordpress/components';
import { memo } from '@wordpress/element';
import { UnitPicker } from '../index';
import './customizer.scss';

export default memo( ( props ) => {
	const {
		value,
		units = [],
		onChange,
		...otherProps
	} = props;

	const update = ( val, type = 'value' ) => {
		const newValue = { ...value, [ type ]: val };
		onChange( newValue );
	};

	return (
		<div className="vite-range">
			<RangeControl
				value={ value?.value || '' }
				onChange={ val => update( val ) }
				{ ...otherProps }
			/>
			{ 0 < units?.length && (
				<>
					<UnitPicker
						onChange={ val => update( '-' === val ? '' : val, 'unit' ) }
						value={ ( value?.unit ?? 'px' ) || '-' }
						units={ units }
					/>
				</>
			) }
		</div>
	);
} );

import { Button, ButtonGroup, Dropdown, RangeControl } from '@wordpress/components';
import { memo } from '@wordpress/element';
import './customizer.scss';
import Select, { Option } from 'rc-select';
import { dropdownIcon } from '../../utils';

const UNITS = [ '-', 'px', 'em', 'rem', '%' ];

export default memo( ( props ) => {
	let {
		value,
		onChange,
		noUnits = true,
		units = UNITS,
		defaultUnit = 'px',
		min = 0,
		max = 300,
		step = 1,
		unitPicker = 'dropdown',
		...otherProps
	} = props;

	max = ( [ 'em', 'rem', '-' ].includes( value?.unit ) || ( ! value?.unit && ! noUnits && '-' === defaultUnit ) ) ? 20 : ( [ 'vh', '%' ].includes( value?.unit ?? defaultUnit ) ? 100 : max );
	step = ( [ 'em', 'rem', '-' ].includes( value?.unit ) || ( ! value?.unit && ! noUnits && '-' === defaultUnit ) ) ? 0.01 : step;

	return (
		<div className="vite-range">
			<RangeControl
				value={ noUnits ? value : value?.value }
				onChange={ v => {
					if ( noUnits ) {
						onChange( v );
					} else {
						onChange( { ...( value || {} ), value: v } );
					}
				} }
				min={ min }
				max={ max }
				step={ step }
				{ ...otherProps }
			/>
			{ ! noUnits && (
				<>
					{ 'dropdown' === unitPicker ? (
						<Dropdown
							className="vite-units"
							position="bottom center"
							renderToggle={ ( { isOpen, onToggle } ) => (
								<Button onClick={ onToggle } aria-expanded={ isOpen }>
									{ value?.unit ?? defaultUnit }
								</Button>
							) }
							renderContent={ ( { onToggle } ) => (
								<ButtonGroup>
									{ units.map( u => (
										<Button
											className={ `vite-unit${ ( value?.unit ?? defaultUnit ) === u ? ' is-primary' : '' }` }
											key={ u }
											onClick={ ( e ) => {
												e.stopPropagation();
												onToggle();
												onChange( { ...( value || {} ), unit: u } );
											} }
										>
											{ u }
										</Button>
									) ) }
								</ButtonGroup>
							) }
						/>
					) : (
						<Select inputIcon={ dropdownIcon() } className="vite-units" value={ value?.unit ?? defaultUnit } onChange={ u => {
							onChange( { ...( value || {} ), unit: u } );
						} }>
							{ units.map( u => (
								<Option
									className={ `vite-unit${ ( value?.unit ?? defaultUnit ) === u ? ' is-primary' : '' }` }
									key={ u }
								>
									{ u }
								</Option>
							) ) }
						</Select>
					) }
				</>
			) }
		</div>
	);
} );

import { Button, ButtonGroup, Dropdown, RangeControl, SelectControl } from '@wordpress/components';
import { memo } from '@wordpress/element';
import './customizer.scss';

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
						<SelectControl
							className="vite-units"
							value={ value?.unit ?? defaultUnit }
							onChange={ u => {
								onChange( { ...( value || {} ), unit: u } );
							} }
							options={ units.map( u => ( { value: u, label: u } ) ) }
						/>
					) }
				</>
			) }
		</div>
	);
} );

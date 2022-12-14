import { Button, ButtonGroup, Dropdown, RangeControl, SelectControl } from '@wordpress/components';
import { memo } from '@wordpress/element';
import './customizer.scss';

// eslint-disable-next-line no-unused-vars
const UNITS = [ '-', 'px', 'em', 'rem', '%' ];

const range = ( start, end ) => {
	start = parseInt( start );
	end = parseInt( end );
	const length = end - start + 1;
	return Array.from( { length }, ( _, i ) => start + i );
};

export default memo( ( props ) => {
	let {
		value,
		onChange,
		units = [],
		defaultUnit = 'px',
		min = 0,
		max = 300,
		step = 1,
		unitPicker = 'dropdown',
		marks,
		input,
		...otherProps
	} = props;

	max = ( [ 'em', 'rem', '-' ].includes( value?.unit ) || ( ! value?.unit && units?.length && '-' === defaultUnit ) ) ? 20 : ( [ 'vh', '%' ].includes( value?.unit ?? defaultUnit ) ? 100 : max );
	step = ( [ 'em', 'rem', '-' ].includes( value?.unit ) || ( ! value?.unit && units?.length && '-' === defaultUnit ) ) ? 0.01 : step;

	return (
		<div className="vite-range">
			<RangeControl
				value={ ! units?.length ? value : value?.value }
				onChange={ v => {
					if ( ! units?.length ) {
						onChange( v );
					} else if ( 1 === units?.length ) {
						onChange( { ...( value || {} ), value: v, unit: units[ 0 ] } );
					} else {
						onChange( { ...( value || {} ), value: v } );
					}
				} }
				min={ min }
				max={ max }
				step={ step }
				marks={ marks ? range( min, max ).map( r => ( {
					label: r,
					value: r,
				} ) ) : undefined }
				withInputField={ input }
				{ ...otherProps }
			/>
			{ units?.length > 0 && (
				<>
					{ 'dropdown' === unitPicker ? (
						<Dropdown
							className="vite-units"
							position="bottom center"
							renderToggle={ ( { isOpen, onToggle } ) => (
								<Button onClick={ onToggle } aria-expanded={ isOpen } style={ { pointerEvents: units.length === 1 ? 'none' : undefined } }>
									{ value?.unit ?? defaultUnit }
								</Button>
							) }
							renderContent={ ( { onToggle } ) => (
								units.length > 1 ? (
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
								) : null
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

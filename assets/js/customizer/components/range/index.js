import { Button, ButtonGroup, Dropdown, RangeControl } from '@wordpress/components';
import { memo } from '@wordpress/element';
import './customizer.scss';
import { splitUnit } from '../../utils';

const UNITS = [ '-', 'px', 'em', 'rem', '%' ];

export default memo( ( props ) => {
	let {
		value = '',
		onChange,
		noUnits = true,
		units = UNITS,
		defaultUnit = 'px',
		min = 0,
		max = 300,
		step = 1,
		...otherProps
	} = props;

	let [ val = '', unit = '' ] = splitUnit( value );
	unit = unit || defaultUnit;
	val = parseFloat( val );

	max = [ 'em', 'rem' ].includes( unit ) ? 20 : ( [ 'vh', '%' ].includes( unit ) ? 100 : max );
	step = [ 'em', 'rem' ].includes( unit ) ? 0.01 : step;

	return (
		<div className="vite-range">
			<RangeControl
				value={ ! isNaN( val ) ? val : undefined }
				onChange={ v => onChange( v + ( ! noUnits ? ( '-' === unit ? '' : unit ) : '' ) ) }
				min={ min }
				max={ max }
				step={ step }
				{ ...otherProps }
			/>
			{ ! noUnits && (
				<Dropdown
					className="vite-units"
					position="bottom center"
					renderToggle={ ( { isOpen, onToggle } ) => (
						<Button onClick={ onToggle } aria-expanded={ isOpen }>
							{ unit }
						</Button>
					) }
					renderContent={ ( { onToggle } ) => (
						<ButtonGroup>
							{ units.map( u => (
								<Button
									className={ `vite-unit${ unit === u ? ' is-primary' : '' }` }
									key={ u }
									onClick={ ( e ) => {
										e.stopPropagation();
										onToggle();
										onChange( `${ val ?? '0' }${ u }` );
									} }
								>
									{ u }
								</Button>
							) ) }
						</ButtonGroup>
					) }
				/>
			) }
		</div>
	);
} );

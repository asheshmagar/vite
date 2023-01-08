import { memo } from '@wordpress/element';
import { Button, ButtonGroup, Dashicon, Dropdown } from '@wordpress/components';
import { withInstanceId, compose } from '@wordpress/compose';
import './customizer.scss';

const SIDES = [
	{ label: 'Top', value: 'top' },
	{ label: 'Right', value: 'right' },
	{ label: 'Bottom', value: 'bottom' },
	{ label: 'Left', value: 'left' },
];

const RADIUS_SIDES = [
	{ label: 'Top Left', value: 'top-left' },
	{ label: 'Top Right', value: 'top-right' },
	{ label: 'Bottom Right', value: 'bottom-right' },
	{ label: 'Bottom Left', value: 'bottom-left' },
];

export default compose( [
	withInstanceId,
	memo,
] )( props => {
	const {
		instanceId,
		value = {},
		onChange,
		step = 1,
		min = 0,
		max = 300,
		sides = [ 'top', 'right', 'bottom', 'left' ],
		units = [ 'px', 'rem', 'em' ],
		defaultUnit = 'px',
		...otherProps
	} = props;

	if ( ! sides?.length ) return null;
	return (
		<div className={ `vite-dimensions${ units?.length > 0 ? ' has-units' : '' }` } { ...otherProps }>
			{ ( sides.some( s => [ 'top-left', 'top-right', 'bottom-right', 'bottom-left' ].includes( s ) ) ? RADIUS_SIDES : SIDES ).map( side => (
				<span key={ side.value } className={ `vite-dimension${ ! sides?.includes( side.value ) ? ' disabled' : '' }` }>
					<input
						type={ sides?.includes( side.value ) ? 'number' : 'text' }
						disabled={ ! sides?.includes( side.value ) }
						onChange={ e => onChange( value?.sync ? ( sides.reduce( ( acc, curr ) => {
							acc[ curr ] = parseFloat( e.target.value );
							return acc;
						}, {} ) ) : { [ side.value ]: parseFloat( e.target.value ) } ) }
						max={ max }
						min={ min }
						step={ step }
						value={ value?.[ side.value ] ?? '' }
						id={ `vite-dimension-${ side.value }-${ instanceId }` }
					/>
					<label htmlFor={ `vite-dimension-${ side.value }-${ instanceId }` } className="vite-dimension-label">{ side.label }</label>
				</span>
			) ) }
			{ /* eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions */ }
			<span className="vite-dimension-sync" onClick={ () => {
				const temp = {};
				if ( ! value?.sync ) {
					const values = Object.values( value ?? {} ).filter( v => 'number' === typeof v );

					if ( values?.length ) {
						const maxV = Math.max( ...values );
						sides.forEach( s => {
							temp[ s ] = maxV;
						} );
					}
				}
				onChange( {
					...temp,
					sync: ! value?.sync,
				} );
			} }>
				<Dashicon icon={ value?.sync ? 'lock' : 'unlock' } />
			</span>
			{ units?.length > 0 && (
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
											onChange( { unit: u } );
										} }
									>
										{ u }
									</Button>
								) ) }
							</ButtonGroup>
						) : null
					) }
				/>
			) }
		</div>
	);
} );

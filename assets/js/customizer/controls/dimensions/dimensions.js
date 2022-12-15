import { memo, useState, RawHTML } from '@wordpress/element';
import { useDeviceSelector } from '../../hooks';
import { DEVICES } from '../../constants';
import { Button, ButtonGroup, Dropdown } from '@wordpress/components';
import { isEqual } from 'lodash';
import { ViteDimensions } from '../../components';

export default memo( ( props ) => {
	let {
		control: {
			setting,
			params: {
				label,
				description,
				inputAttrs: {
					responsive = false,
					units = [ 'px', 'em', 'rem' ],
					defaultUnit = 'px',
					step = 1,
					min = 0,
					max = 300,
					allow_reset: allowReset = true,
					sides = [ 'top', 'right', 'bottom', 'left' ],
				},
				default: defaultValue,
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() ?? {} );
	const { device, DeviceSelector } = useDeviceSelector();

	const unit = responsive ? ( value?.[ device ]?.unit || defaultUnit ) : ( value?.unit || defaultUnit );

	max = [ 'em', 'rem' ].includes( unit ) ? 20 : ( [ 'vh', '%' ].includes( unit ) ? 100 : max );
	step = [ 'em', 'rem' ].includes( unit ) ? 0.01 : step;

	const Units = () => (
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
								setValue( prev => {
									prev = {
										...( prev || {} ),
										...(
											responsive ?
												{
													[ device ]: {
														...prev?.[ device ],
														unit: u,
													},
												} : {
													unit: u,
												}
										),
									};
									setting.set( prev );
									return prev;
								} );
							} }
						>
							{ u }
						</Button>
					) ) }
				</ButtonGroup>
			) }
		/>
	);

	return (
		<div className="vite-control vite-dimensions-control">
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
					{ responsive && <DeviceSelector /> }
					<Units />
				</div>
			) }
			<div className="vite-control-body">
				{ responsive ? (
					DEVICES.map( ( d ) => (
						<ViteDimensions
							key={ d }
							sides={ sides }
							style={ { display: d === device ? 'grid' : 'none' } }
							value={ value?.[ d ] ?? {} }
							onChange={ ( v ) => {
								setValue( prev => {
									prev = {
										...( prev || {} ),
										[ d ]: {
											...( prev?.[ d ] || {} ),
											...v,
										},
									};
									setting.set( prev );
									return prev;
								} );
							} }
							max={ max }
							min={ min }
							step={ step }
							units={ [] }
						/>
					) )
				) : (
					<ViteDimensions
						value={ value ?? {} }
						onChange={ ( v ) => {
							setValue( prev => {
								prev = {
									...( prev || {} ),
									...v,
								};
								setting.set( prev );
								return prev;
							} );
						} }
						max={ max }
						min={ min }
						step={ step }
						sides={ sides }
						units={ [] }
					/>
				) }
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

import { memo, useState, RawHTML } from '@wordpress/element';
import { useDeviceSelector } from '../../hooks';
import { DEVICES } from '../../constants';
import { Button, ButtonGroup, Dropdown } from '@wordpress/components';
import { isEqual } from 'lodash';
import { ViteDimensions } from '../../components';
import { ControlPropsType } from '../types';

const Dimensions: React.FC<ControlPropsType> = ( props ) => {
	let {
		control: {
			id,
			setting,
			params: {
				label,
				description,
				inputAttrs: {
					responsive = false,
					units = [ 'px', 'em', 'rem' ],
					default_unit: defaultUnit = 'px',
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

	sides = -1 !== id.indexOf( 'radius' ) ? [ 'top-left', 'top-right', 'bottom-right', 'bottom-left' ] : sides;

	const unit = responsive ? ( value?.[ device ]?.unit ?? ( value?.desktop?.unit ?? 'px' ) ) : ( value?.unit || defaultUnit );

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
					{ units.map( ( u: string ) => (
						<Button
							className={ `vite-unit${ unit === u ? ' is-primary' : '' }` }
							key={ u }
							onClick={ ( e: React.SyntheticEvent ) => {
								e.stopPropagation();
								onToggle();
								setValue( ( prev: any ) => {
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
						// @ts-ignore
						<ViteDimensions
							key={ d }
							sides={ sides }
							style={ { display: d === device ? 'grid' : 'none' } }
							value={ value?.[ d ] ?? ( value?.desktop ?? {} ) }
							onChange={ ( v: any ) => {
								setValue( ( prev: any ) => {
									prev = {
										...( prev || {} ),
										[ d ]: {
											...( prev?.[ d ] || {} ),
											...v,
											unit: prev?.[ d ]?.unit || defaultUnit,
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
					// @ts-ignore
					<ViteDimensions
						value={ value ?? {} }
						onChange={ ( v: any ) => {
							setValue( ( prev: any ) => {
								prev = {
									...( prev || {} ),
									...v,
									unit: prev?.unit || defaultUnit,
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
				<RawHTML className="customize-control-description">{ description }</RawHTML>
			) }
		</div>
	);
};

export default memo( Dimensions );

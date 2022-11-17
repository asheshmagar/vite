import { memo, useState, RawHTML } from '@wordpress/element';
import { useDeviceSelector } from '../../hooks';
import { DEVICES } from '../../constants';
import { Button, ButtonGroup, Dashicon, Dropdown } from '@wordpress/components';
import { isEqual } from 'lodash';

/* eslint-disable jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions */

const SIDES = [
	{ label: 'Top', value: 'top' },
	{ label: 'Right', value: 'right' },
	{ label: 'Bottom', value: 'bottom' },
	{ label: 'Left', value: 'left' },
];

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
					{ ! isEqual( defaultValue, value ) && (
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
						<div key={ d } style={ { display: d === device ? 'grid' : 'none' } } className="vite-dimensions">
							{ SIDES.map( ( side ) => (
								<span key={ side.value } className="vite-dimension">
									<input
										type="number"
										value={ value?.[ d ]?.[ side.value ] ?? '' }
										onChange={ ( e ) => {
											setValue( prev => {
												prev = { ...prev,
													[ d ]: {
														...( prev?.[ d ] || {} ),
														...(
															prev?.[ d ]?.sync ?
																{
																	top: e.target.value,
																	right: e.target.value,
																	bottom: e.target.value,
																	left: e.target.value,
																} : {
																	[ side.value ]: e.target.value,
																}
														),
													},
												};
												setting.set( prev );
												return prev;
											} );
										} }
										max={ max }
										min={ min }
										step={ step }
									/>
									<span className="vite-dimension-label">{ side.label }</span>
								</span>
							) ) }
							<span className="vite-dimension-sync" onClick={ () => {
								setValue( prev => {
									prev = { ...( prev || {} ),
										[ d ]: {
											...prev?.[ d ],
											sync: ! prev?.[ d ]?.sync,
										},
									};
									setting.set( prev );
									return prev;
								} );
							} }>
								<Dashicon icon={ value?.[ d ]?.sync ? 'lock' : 'unlock' } />
							</span>
						</div>
					) )
				) : (
					<div className="vite-dimensions">
						{ SIDES.map( ( side ) => (
							<span key={ side.value } className="vite-dimension">
								<input
									type="number"
									value={ value?.[ side.value ] ?? '' }
									onChange={ ( e ) => {
										setValue( prev => {
											prev = {
												...prev,
												...(
													prev?.sync ?
														{
															top: e.target.value,
															right: e.target.value,
															bottom: e.target.value,
															left: e.target.value,
														} : {
															[ side.value ]: e.target.value,
														}
												),
											};
											setting.set( prev );
											return prev;
										} );
									} }
									max={ max }
									min={ min }
									step={ step }
								/>
								<span className="vite-dimension-label">{ side.label }</span>
							</span>
						) ) }
						<span className="vite-dimension-sync" onClick={ () => {
							const maxSide = Object.entries( value ?? {} )
								.filter( ( [ k ] ) => ! [ 'unit', 'sync' ].includes( k ) )
								.reduce( ( acc, [ , v = '' ] ) => {
									v = isNaN( v ) ? 0 : parseFloat( v );
									return v > acc ? v : acc;
								} );
							setValue( prev => {
								prev = {
									...( prev || {} ),
									sync: ! prev?.sync,
								};
								setting.set( prev );
								return prev;
							} );
						} }>
							<Dashicon icon={ value?.sync ? 'lock' : 'unlock' } />
						</span>
					</div>
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

import { memo, useState, useCallback, useEffect, Fragment } from '@wordpress/element';
import { cloneDeep, isEqual } from 'lodash';
import { usePortal, useDeviceSelector } from '../../hooks';
import { ReactSortable } from 'react-sortablejs';
import { sprintf, __ } from '@wordpress/i18n';
import { ButtonGroup, Button } from '@wordpress/components';

const Item = ( {
	item,
	row,
	area,
	device,
	choices,
	remove,
	customizer,
} ) => {
	const [ isActive, setIsActive ] = useState( false );
	const section = choices?.[ device ]?.[ item.id ]?.section ?? null;

	useEffect( () => {
		if ( section ) {
			customizer.section( section )?.expanded?.bind( setIsActive );
		}
	}, [] );
	return (
		// eslint-disable-next-line
		<div className={ `vite-builder-component${ isActive ? ' is-active' : '' }` }
			onClick={ section ? () => customizer.section( section )?.focus() : null }>
			<span className="vite-builder-component-title">
				{ choices?.[ device ]?.[ item.id ]?.name }
			</span>
			<span
				role="button"
				tabIndex={ -1 }
				onKeyDown={ () => {} }
				className="vite-builder-component-handle"
				dangerouslySetInnerHTML={ {
					__html: sprintf( window?._VITE_CUSTOMIZER_?.icons?.xmark, 'vite-icon', 10, 10 ),
				} }
				onClick={ () => remove( row, area, item ) }
			/>
		</div>
	);
};

const range = ( start, end ) => {
	const length = end - start + 1;
	return Array.from( { length }, ( _, i ) => start + i );
};

export default memo( ( props ) => {
	const {
		control: {
			id,
			params: {
				inputAttrs: {
					areas = {},
				},
				choices,
				section,
			},
			setting,
		},
		customizer,
		context,
	} = props;

	const [ value, setValue ] = useState( setting.get() || {} );
	const [ open, setOpen ] = useState( false );
	const [ col, setCol ] = useState( null );
	const Portal = usePortal( document.querySelector( '.wp-full-overlay' ) );
	let { device, setDevice } = useDeviceSelector();

	if ( 'header' === context ) {
		device = 'desktop' === device ? 'desktop' : 'mobile';
	} else {
		device = 'desktop';
	}

	useEffect( () => {
		if ( 'footer' !== context ) return;
		const cols = {};

		for ( const pos of [ 'top', 'main', 'bottom' ] ) {
			const rowSetting = customizer( `vite[footer-${ pos }-row-cols]` );
			cols[ pos ] = rowSetting?.get() ?? null;

			rowSetting?.bind( ( v ) => {
				setCol( prev => ( { ...prev, [ pos ]: v } ) );
				if ( parseInt( v ) < 6 ) {
					setValue( prev => {
						const data = cloneDeep( prev );
						for ( const i of range( parseInt( v ), 6 ) ) {
							if ( data?.desktop?.[ pos ]?.[ i + 1 ]?.length ) {
								data.desktop[ pos ][ i + 1 ] = [];
							}
						}
						setting.set( data );
						return data;
					} );
				}
			} );
		}
		setCol( cols );
	}, [] );

	const update = ( row, area, items, screen = 'desktop' ) => {
		const newValue = cloneDeep( value );
		newValue[ screen ][ row ][ area ] = items;
		if ( ! isEqual( newValue, value ) ) {
			setValue( newValue );
			setting.set( newValue );
		}
	};

	const remove = ( row, area, item ) => {
		const newValue = cloneDeep( value );
		newValue[ device ][ row ][ area ] = ( newValue?.[ device ]?.[ row ]?.[ area ] || [] ).filter( v => v.id !== item.id );
		if ( ! isEqual( newValue, value ) ) {
			setValue( newValue );
			setting.set( newValue );
		}
	};

	const getAreaItems = useCallback( ( row, area, d ) => value?.[ d ]?.[ row ]?.[ area ] || [], [ value, device ] );

	useEffect( () => {
		const sec = customizer.section( section );
		const panel = sec?.panel();
		if ( panel ) {
			customizer.panel( panel ).expanded.bind( setOpen );
		}
	}, [] );

	return (
		<Portal>
			<div className="vite-builder-portal" data-builder-open={ open } data-builder={ context }>
				{ 'header' === context && (
					<div className="vite-builder-portal-header">
						<div className="vite-builder-portal-header-left">
							<ButtonGroup>
								{ [ {
									id: 'desktop',
									label: __( 'Desktop', 'vite' ),
								}, {
									id: 'mobile',
									label: __( 'Tablet / Mobile', 'vite' ),
								} ].map( d => (
									<Button
										key={ d.id }
										onClick={ () => setDevice( 'mobile' === d.id ? 'tablet' : d.id ) }
										icon={ 'mobile' === d.id ? 'tablet' : d.id }
										className={ device === d.id ? 'active' : '' }
									>
										{ d.label }
									</Button>
								) ) }
							</ButtonGroup>
						</div>
					</div>
				) }
				<div className="vite-builder-portal-content">
					<div className="vite-builder-portal-content-inner" data-desktop={ device }>
						{ ( 'header' === context ? [ 'desktop', 'mobile' ] : [ 'desktop' ] ).map( d => (
							<Fragment key={ d }>
								{ 'mobile' === d && (
									<div className="vite-builder-mobile-offset"
										style={ { display: 'mobile' !== device ? 'none' : undefined } }>
										<div className="vite-builder-mobile-offset-inner">
											<ReactSortable
												forceFallback={ true }
												list={ value.offset ?? [] }
												setList={ ( list ) => {
													const val = cloneDeep( value );
													val.offset = list;
													if ( ! isEqual( val, value ) ) {
														setValue( val );
														setting.set( val );
													}
												} }
												animation={ 150 }
												className="vite-builder-area"
												fallbackClass="vite-builder-component-fallback"
												ghostClass="vite-builder-component-placeholder"
												chosenClass="vite-builder-component-chosen"
												dragClass="vite-builder-component-dragging"
												group={ {
													name: `vite-builder[${ id }]`,
													pull: true,
													put: true,
												} }
											>
												{ value?.offset?.map( ( item ) => (
													// eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions
													<div className="vite-builder-component" key={ item.id }
														onClick={ choices?.mobile?.[ item.id ]?.section ? () => customizer.section( choices.mobile[ item.id ].section )?.focus() : undefined }>
														<span className="vite-builder-component-title">
															{ choices?.[ device ]?.[ item.id ]?.name }
														</span>
														<span
															role="button"
															tabIndex={ -1 }
															onKeyDown={ () => {
															} }
															className="vite-builder-component-handle"
															dangerouslySetInnerHTML={ {
																__html: sprintf( window?._VITE_CUSTOMIZER_?.icons?.xmark, 'vite-icon', 10, 10 ),
															} }
															onClick={ () => {
																const val = cloneDeep( value );
																val.offset = val.offset.filter( ( i ) => i.id !== item.id );
																if ( ! isEqual( val, value ) ) {
																	setValue( val );
																	setting.set( val );
																}
															} }
														/>
													</div>
												) ) }
											</ReactSortable>
											<Button className="vite-builder-cta" icon={ 'admin-generic' }
												onClick={ customizer.section( `vite[header-offset]` ) ? customizer.section( `vite[header-offset]` ).focus() : undefined } />
										</div>
									</div>
								) }
								<div key={ d } className="vite-builder-rows"
									style={ { display: d !== device ? 'none' : undefined } }>
									{
										Object.entries( areas ).map( ( [ row = '', columns = {} ] ) => (
											<div key={ row } data-row={ row } className="vite-builder-row"
												data-cols={ 'footer' === context ? ( col?.[ row ] ?? '' ) : undefined }>
												<div className="vite-builder-cols">
													{ Object.keys( columns ).map( ( area ) => (
														<div className="vite-col" data-col={ area } key={ area }
															data-pos={ 'footer' === context ? ( parseInt( area ) === parseInt( col?.[ row ] ?? '' ) ? 'last' : undefined ) : undefined }
															data-hidden={ 'footer' === context ? ( parseInt( area ) <= parseInt( col?.[ row ] ?? '' ) ) : undefined }>
															<ReactSortable
																forceFallback={ true }
																list={ getAreaItems( row, area, d ) }
																setList={ ( list ) => update( row, area, list, d ) }
																className="vite-builder-area"
																fallbackClass="vite-builder-component-fallback"
																ghostClass="vite-builder-component-placeholder"
																chosenClass="vite-builder-component-chosen"
																dragClass="vite-builder-component-dragging"
																group={ {
																	name: `vite-builder[${ id }]`,
																	pull: true,
																	put: true,
																} }
																animation={ 150 }
															>
																{ getAreaItems( row, area, d ).map( ( item ) => (
																	<Item key={ item.id } { ...{
																		row,
																		area,
																		item,
																		device,
																		customizer,
																		remove,
																		choices,
																	} } />
																) ) }
															</ReactSortable>
														</div>
													) ) }
												</div>
												<Button className="vite-builder-cta" icon={ 'admin-generic' }
													onClick={ 'header' === context ? customizer.section( `vite[header-${ row }-row]` ) ? () => customizer.section( `vite[header-${ row }-row]` ).focus() : undefined : customizer.section( `vite[footer-${ row }-row]` ) ? () => customizer.section( `vite[footer-${ row }-row]` ).focus() : undefined } />
											</div>
										) )
									}
								</div>
							</Fragment>
						) ) }
					</div>
				</div>
			</div>
		</Portal>
	);
} );

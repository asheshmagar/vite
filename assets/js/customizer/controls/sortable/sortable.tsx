import { memo, useState, RawHTML, useEffect } from '@wordpress/element';
import { ReactSortable } from 'react-sortablejs';
import { isArray, differenceBy, noop } from 'lodash';
import { sprintf } from '@wordpress/i18n';
import Select from 'react-select';
import { Button } from '@wordpress/components';
import { ControlPropsType } from '../types';

type SortableItemPropsType = {
	item: any;
	innerItems?: ( () => React.ReactNode )|null;
	toggleItem: () => void;
	sort?: boolean;
	removable?: boolean;
	onRemove?: () => void;
}

const SortableItem: React.FC<SortableItemPropsType> = ( {
	item,
	innerItems = null,
	toggleItem,
	sort,
	removable = false,
	onRemove = noop,
} ) => {
	const [ open, setOpen ] = useState( false );
	return (
		<li className="vite-sortable-item" data-open={ open } data-sort-disabled={ sort ? undefined : true }>
			<div className="vite-sortable-item-actions">
				<RawHTML
					className="vite-visibility"
					onClick={ toggleItem }
				>
					{ sprintf(
						item?.visible ?
							window?._VITE_CUSTOMIZER_?.icons?.eye :
							window?._VITE_CUSTOMIZER_?.icons?.[ 'eye-slash' ],
						'vite-icon',
						14,
						14
					) }
				</RawHTML>
				<div className="vite-label">{ item.label }</div>
				{ ( innerItems && ! removable ) && (
					<RawHTML className="vite-toggle" onClick={ () => setOpen( prev => ! prev ) }>
						{ sprintf( window?._VITE_CUSTOMIZER_?.icons?.[ 'caret-down' ], 'vite-icon', 14, 14 ) }
					</RawHTML>
				) }
				{ removable && (
					<RawHTML className="vite-remove" onClick={ onRemove }>
						{ sprintf( window?._VITE_CUSTOMIZER_?.icons?.xmark, 'vite-icon', 14, 14 ) }
					</RawHTML>
				) }
			</div>
			{ ( innerItems && open ) && (
				<div className="vite-sortable-item-inner">
					{ innerItems() }
				</div>
			) }
		</li>
	);
};

type ChoiceType = {
	id: string;
	label: string;
}[];

const Sortable: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			id,
			params: {
				label,
				description,
				choices,
				inputAttrs: {
					sort = true,
					idWithInnerItems = false,
					innerItems = [],
					removable = false,
				},
			},
			setting,
		},
	} = props;

	const [ value, setValue ] = useState( () => {
		let val = setting.get();

		if ( ! isArray( val ) ) {
			val = [];
		}

		if ( removable ) {
			return val;
		}

		if ( ! val?.length && choices ) {
			val = ( choices as ChoiceType ).map( ( c ) => ( {
				...c,
				visible: false,
				items: idWithInnerItems && c.id?.startsWith( idWithInnerItems ) ?
					innerItems.map( ( i: any ) => ( {
						...i,
						visible: false,
					} ) ) :
					undefined,
				chosen: false,
				selected: false,
			} ) );
		}

		return val.map( ( v: any ) => ( {
			...v,
			label: ( choices as ChoiceType ).find( c => v?.id?.startsWith( c?.id ) )?.label,
			items: idWithInnerItems && v?.id?.startsWith( idWithInnerItems ) ?
				innerItems.map( ( i: any ) => ( {
					...i,
					visible: v?.items.find( ( ii: any ) => i?.id === ii?.id )?.visible ?? false,
					chosen: false,
					selected: false,
				} ) ) :
				undefined,
			chosen: false,
			selected: false,
		} ) );
	} );

	const getSelected = () => {
		const availableItems = differenceBy( ( choices as ChoiceType ) ?? [], value ?? [], 'id' );
		return availableItems?.length ? availableItems[ 0 ] : null;
	};

	const [ selected, setSelected ] = useState( getSelected );

	useEffect( () => {
		setSelected( getSelected );
	}, [ value ] );

	return (
		<div className="vite-control vite-sortable-control" data-control-id={ id }>
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			{ description && (
				<RawHTML className="customize-control-description">{ description }</RawHTML>
			) }
			<div className="vite-control-body">
				<ReactSortable
					forceFallback={ true }
					fallbackClass="vite-sortable-fallback"
					sort={ sort }
					disabled={ ! sort }
					animation={ 150 }
					tag="ul"
					className={ `vite-sortable` }
					list={ value }
					setList={ v => {
						if ( JSON.stringify( value ) !== JSON.stringify( v ) ) {
							setting.set( v );
							setValue( v );
						}
					} }
				>
					{ value.map( ( item: any, idx: number, arr: any ) => (
						<SortableItem
							key={ item.id }
							item={ item }
							sort={ sort }
							toggleItem={ () => {
								const temp = [ ...arr ];
								temp[ idx ] = {
									...temp[ idx ],
									visible: ! temp[ idx ].visible,
								};
								setValue( temp );
								setting.set( temp );
							} }
							removable={ removable }
							onRemove={ () => {
								setValue( ( prev: any ) => {
									prev = prev.filter( ( i: any ) => i.id !== item.id );
									setting.set( prev );
									return prev;
								} );
							} }
							innerItems={ idWithInnerItems && item.id?.startsWith( idWithInnerItems ) && item?.items ? () => {
								return (
									<ReactSortable
										forceFallback={ true }
										fallbackClass="vite-sortable-fallback"
										sort={ sort }
										disabled={ ! sort }
										animation={ 150 }
										tag="ul"
										className={ `vite-sortable` }
										list={ item.items }
										setList={ ( v ) => {
											const temp = [ ...arr ];
											if ( JSON.stringify( temp[ idx ]?.items ) !== JSON.stringify( v ) ) {
												temp[ idx ] = {
													...temp[ idx ],
													items: v,
												};
												setValue( temp );
												setting.set( temp );
											}
										} }
									>
										{ item.items.map( ( i: any ) => {
											return (
												<li key={ i.id } className="vite-sortable-item">
													<div className="vite-sortable-item-actions">
														<RawHTML
															className="vite-visibility"
															onClick={ () => {
																const temp = [ ...arr ];
																temp[ idx ] = {
																	...temp[ idx ],
																	items: temp[ idx ].items.map( ( ii: any ) => ( {
																		...ii,
																		visible: ii.id === i.id ? ! ii.visible : ii.visible,
																	} ) ),
																};
																setValue( temp );
																setting.set( temp );
															} }
														>
															{ sprintf(
																i?.visible ?
																	window?._VITE_CUSTOMIZER_?.icons?.eye :
																	window?._VITE_CUSTOMIZER_?.icons?.[ 'eye-slash' ],
																'vite-icon',
																14,
																14
															) }
														</RawHTML>
														<div className="vite-label">{ i.label }</div>
													</div>
												</li>
											);
										} ) }
									</ReactSortable>
								);
							} : null }
						/>
					) ) }
				</ReactSortable>
				{ removable && (
					<div className="vite-sortable-available-items">
						<Select
							value={ selected }
							isSearchable={ true }
							onChange={ ( v ) => setSelected( v ) }
							options={ differenceBy( choices as ChoiceType, value, 'id' ) }
							classNamePrefix="vite-select"
							className="vite-select"
							isMulti={ false }
							components={ {
								IndicatorSeparator: () => null,
								// @ts-ignore
								DropdownIndicator: ( { size = 10 } ) => (
									<svg height={ size } width={ size } viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
										<path d="M21.707,8.707l-9,9a1,1,0,0,1-1.414,0l-9-9A1,1,0,1,1,3.707,7.293L12,15.586l8.293-8.293a1,1,0,1,1,1.414,1.414Z" />
									</svg>
								) } }
						/>
						<Button
							isPrimary={ true }
							isSmall={ true }
							onClick={ () => {
								if ( selected ) {
									setValue( ( prev: any ) => {
										prev = [
											...prev,
											{
												...selected,
												visible: true,
											},
										];
										setting.set( prev );
										return prev;
									} );
								}
							} }
						>
							Add
						</Button>
					</div>
				) }
			</div>
		</div>
	);
};

export default memo( Sortable );

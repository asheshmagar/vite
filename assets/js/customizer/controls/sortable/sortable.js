import { memo, useState, RawHTML } from '@wordpress/element';
import { ReactSortable } from 'react-sortablejs';
import { isArray } from 'lodash';
import { sprintf } from '@wordpress/i18n';

const SortableItem = ( { item, innerItems = false, toggleItem, sort } ) => {
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
				{ !! innerItems && (
					<RawHTML className="vite-toggle" onClick={ () => setOpen( prev => ! prev ) }>
						{ sprintf( window?._VITE_CUSTOMIZER_?.icons?.[ 'caret-down' ], 'vite-icon', 14, 14 ) }
					</RawHTML>
				) }
			</div>
			{ ( !! innerItems && open ) && (
				<div className="vite-sortable-item-inner">
					{ innerItems() }
				</div>
			) }
		</li>
	);
};

export default memo( ( props ) => {
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

		if ( ! val?.length ) {
			val = choices.map( ( c ) => ( {
				...c,
				visible: false,
				items: idWithInnerItems && c.id?.startsWith( idWithInnerItems ) ?
					innerItems.map( i => ( {
						...i,
						visible: false,
					} ) ) :
					undefined,
				chosen: false,
				selected: false,
			} ) );
		}

		return val.map( v => ( {
			...v,
			label: choices.find( c => v?.id?.startsWith( c?.id ) )?.label,
			items: idWithInnerItems && v?.id?.startsWith( idWithInnerItems ) ?
				innerItems.map( i => ( {
					...i,
					visible: v?.items.find( ii => i?.id === ii?.id )?.visible ?? false,
					chosen: false,
					selected: false,
				} ) ) :
				undefined,
			chosen: false,
			selected: false,
		} ) );
	} );

	return (
		<div className="vite-control vite-sortable-control" data-control-id={ id }>
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
			<div className="vite-control-body">
				<ReactSortable
					forceFallback={ true }
					fallbackClass="vite-sortable-fallback"
					sort={ !! sort }
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
					{ value.map( ( item, idx, arr ) => (
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
							innerItems={ idWithInnerItems && item.id?.startsWith( idWithInnerItems ) && item?.items ? () => {
								return (
									<ReactSortable
										forceFallback={ true }
										fallbackClass="vite-sortable-fallback"
										sort={ !! sort }
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
										{ item.items.map( i => {
											return (
												<li key={ i.id } className="vite-sortable-item">
													<div className="vite-sortable-item-actions">
														<RawHTML
															className="vite-visibility"
															onClick={ () => {
																const temp = [ ...arr ];
																temp[ idx ] = {
																	...temp[ idx ],
																	items: temp[ idx ].items.map( ii => ( {
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
							} : false }
						/>
					) ) }
				</ReactSortable>
			</div>
		</div>
	);
} );

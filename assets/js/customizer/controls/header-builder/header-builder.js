import { memo, useState, useCallback, useEffect, useRef, useMemo } from '@wordpress/element';
import { cloneDeep, isEqual } from 'lodash';
import { Button } from '@wordpress/components';
import { usePortal, useDeviceSelector } from '../../hooks';
import { ReactSortable } from 'react-sortablejs';
import { sprintf } from '@wordpress/i18n';

export default memo( ( props ) => {
	const {
		control: {
			id,
			params: {
				inputAttrs: {
					areas = {},
				},
				choices,
			},
			setting,
			section,
		},
		customizer,
	} = props;

	const [ value, setValue ] = useState( setting.get() );
	// const [ open, setOpen ] = useState( false );
	const Portal = usePortal( document.querySelector( '.wp-full-overlay' ) );
	const { device, setDevice } = useDeviceSelector();
	//
	const update = ( row, area, items ) => {
		const newValue = cloneDeep( value );
		newValue[ device ][ row ][ area ] = items;
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

	const getAreaItems = useCallback( ( row, area ) => value?.[ device ]?.[ row ]?.[ area ] || [], [ value ] );

	const items = Object.values( value?.[ 'desktop' === device ? 'desktop' : 'mobile' ] ).reduce( ( acc, v ) => {
		for ( const val of Object.values( v ) ) {
			if ( val?.length ) {
				for ( const item of val ) {
					acc = [ ...acc, item.id ];
				}
			}
		}
		return acc;
	}, [] );

	const availableItems = Object.entries( choices ).map( ( [ item, val ] ) => ( {
		id: item,
		label: val.name,
		section: val.section,
		disabled: items.includes( item ),
	} ) );

	return (
		<div className="vite-control vite-header-builder-control">
			<div className="vite-builder-available-items">
				{ availableItems.map( ( item ) => (
					<ReactSortable
						forceFallback={ true }
						list={ [ item ] }
						setList={ () => {} }
						key={ item.id }
						className="vite-builder-available-item"
						fallbackClass="vite-builder-item-fallback"
						ghostClass="vite-builder-item-placeholder"
						chosenClass="vite-builder-item-chosen"
						dragClass="vite-builder-item-dragging"
						disabled={ item.disabled }
						group={ {
							name: `vite-builder[${ id }]`,
							pull: 'clone',
							put: false,
						} }
					>
						<div className="vite-available-item">
							<span className="vite-builder-item-title">
								{ item.label }
							</span>
							<span
								className="vite-builder-item-handle"
								dangerouslySetInnerHTML={ {
									__html: sprintf( window?._VITE_CUSTOMIZER_?.icons?.[ item.disabled ? 'chevron-right' : 'arrows-up-down-left-right' ], 'vite-icon', 10, 10 ),
								} }
							/>
						</div>
					</ReactSortable>
				) ) }
			</div>
			<Portal>
				<div className="vite-builder-portal">
					<div className="vite-builder-portal-header">
					</div>
					<div className="vite-builder-portal-content">
						{ [ 'desktop', 'mobile' ].map( d => (
							<div key={ d } className="vite-builder-rows" style={ { display: d !== device ? 'none' : undefined } }>
								{
									Object.entries( areas ).map( ( [ row, columns = { } ] ) => (
										<div key={ row } data-row={ row } className="vite-builder-row">
											<div className="vite-builder-cols">
												{ Object.keys( columns ).map( ( area ) => (
													<div className="vite-col" data-col={ area } key={ area }>
														<ReactSortable
															forceFallback={ true }
															list={ getAreaItems( row, area ) }
															setList={ ( list ) => update( row, area, list ) }
															className="vite-builder-area"
															fallbackClass="vite-builder-item-fallback"
															ghostClass="vite-builder-item-placeholder"
															chosenClass="vite-builder-item-chosen"
															dragClass="vite-builder-item-dragging"
															group={ {
																name: `vite-builder[${ id }]`,
																pull: true,
																put: true,
															} }
														>
															{ getAreaItems( row, area ).map( ( item ) => (
																<div className="vite-builder-item" key={ item.id }>
																	<span className="vite-builder-item-title">
																		{ choices?.[ item.id ]?.name }
																	</span>
																	{ /* eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions */ }
																	<span
																		className="vite-builder-item-handle"
																		dangerouslySetInnerHTML={ {
																			__html: sprintf( window?._VITE_CUSTOMIZER_?.icons?.xmark, 'vite-icon', 10, 10 ),
																		} }
																		onClick={ () => remove( row, area, item ) }
																	/>
																</div>
															) ) }
														</ReactSortable>
													</div>
												) ) }
											</div>
										</div>
									) )
								}
							</div>
						) ) }
					</div>
				</div>
			</Portal>
		</div>
	);
} );

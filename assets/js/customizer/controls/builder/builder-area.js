import { memo, useMemo, useState } from '@wordpress/element';
import { ReactSortable } from 'react-sortablejs';
import { Button, Popover, ButtonGroup, Dashicon } from '@wordpress/components';
import { getObjectValues } from '../../utils';
import { __ } from '@wordpress/i18n';

export default memo( ( props ) => {
	const [ isOpen, setIsOpen ] = useState( false );
	const {
		areaItems = [],
		value = {},
		update,
		control: {
			id,
			params: {
				choices = {},
			},
		},
		remove,
		className,
		col,
	} = props;

	const sortableList = useMemo( () => areaItems.map( v => ( { id: v } ) ), [ areaItems ] );

	const currentAvailableItems = useMemo( () => {
		const savedItems = getObjectValues( value ) || [];
		return Object.keys( choices ).filter( i => -1 === savedItems.indexOf( i ) );
	}, [ value ] );

	return (
		<>
			<div className={ className } data-col={ col }>
				<ReactSortable
					forceFallback={ true }
					fallbackClass="vite-builder-item-fallback"
					ghostClass="vite-builder-item-placeholder"
					chosenClass="vite-builder-item-chosen"
					dragClass="vite-builder-item-dragging"
					group={ `vite-builder-group-${ id }` }
					className={ 'vite-builder-droppable' }
					tag={ 'div' }
					list={ sortableList }
					setList={ list => {
						let newList = [];
						if ( list?.length > 0 ) {
							newList = list.map( li => li.id );
						}
						update( newList );
					} }
				>
					{ sortableList.map( li => (
						<div className="vite-builder-item" key={ li.id }>
							<span className="vite-builder-item-handle">
								<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" aria-hidden="true" focusable="false">
									<path d="M5 4h2V2H5v2zm6-2v2h2V2h-2zm-6 8h2V8H5v2zm6 0h2V8h-2v2zm-6 6h2v-2H5v2zm6 0h2v-2h-2v2z" />
								</svg>
							</span>
							<span className="vite-builder-item-title">
								{ choices?.[ li.id ]?.name || '' }
							</span>
							{ choices?.[ li.id ]?.section && (
								<Button icon="admin-generic" onClick={ () => {} } />
							) }
							<Button icon="no-alt" onClick={ () => remove( li.id ) } />
						</div>
					) ) }

				</ReactSortable>
				<Button icon="plus" className="vite-builder-popover-trigger" onClick={ () => setIsOpen( open => ! open ) } />
				{ isOpen && (
					<Popover
						position="top center"
						noArrow={ false }
						className="vite-builder-components-popover"
						onClose={ () => setIsOpen( false ) }
						onFocusOutside={ () => setIsOpen( false ) }
					>
						<div className="vite-builder-components">
							{ currentAvailableItems?.length > 0 ? (
								<ButtonGroup className="vite-builder-components-group" >
									{ currentAvailableItems.map( item => (
										<Button className="vite-builder-component" key={ item } onClick={ () => {
											update( [ ...areaItems, item ] );
											setIsOpen( false );
										} }>
											{ choices?.[ item ]?.icon && <Dashicon icon={ choices[ item ].icon } /> }
											<span className="vite-builder-component-title">
												{ choices?.[ item ]?.name || '' }
											</span>
										</Button>
									) ) }
								</ButtonGroup>
							) : (
								<div className="vite-builder-components-group">
									<div
										className="vite-builder-component-none"
										style={ { gridColumn: '1/-1', textAlign: 'center' } }
									>
										{ __( 'No components' ) }
									</div>
								</div>
							) }
						</div>
					</Popover>
				) }
			</div>
		</>
	);
} );

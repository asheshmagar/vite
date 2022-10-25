import { useMemo, memo, useState } from '@wordpress/element';
import { ReactSortable } from 'react-sortablejs';
import { getObjectValues } from '../../utils';
import { __ } from '@wordpress/i18n';

export default memo( ( props ) => {
	const {
		control,
		control: {
			params: {
				choices = {},
			},
		},
		value,
	} = props;
	const [ availableItems ] = useState( Object.keys( choices ) );

	const currentAvailableItems = useMemo( () => {
		const savedItems = getObjectValues( value ) || [];
		return availableItems.filter( i => -1 === savedItems.indexOf( i ) ).map( i => ( { id: i } ) );
	}, [ value ] );

	return (
		<>
			<span className="customize-control-title">{ __( 'Available Components' ) }</span>
			<div className="vite-builder-available-items">
				{ currentAvailableItems.map( ( item ) => (
					<ReactSortable
						forceFallback={ true }
						fallbackClass="vite-builder-item-fallback"
						ghostClass="vite-builder-item-placeholder"
						chosenClass="vite-builder-item-chosen"
						dragClass="vite-builder-item-dragging"
						key={ item.id }
						className="vite-builder-available-items-list"
						list={ [ item ] }
						setList={ () => {} }
						tag="div"
						group={ { name: `vite-builder-group-${ control.id }`, put: 'clone' } }
					>
						<div className="vite-available-item">
							<span className="vite-builder-item-handle">
								<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" aria-hidden="true" focusable="false">
									<path d="M5 4h2V2H5v2zm6-2v2h2V2h-2zm-6 8h2V8H5v2zm6 0h2V8h-2v2zm-6 6h2v-2H5v2zm6 0h2v-2h-2v2z" />
								</svg>
							</span>
							<span className="vite-builder-item-title">
								{ choices?.[ item.id ]?.name || '' }
							</span>
						</div>
					</ReactSortable>
				) ) }
			</div>
		</>
	);
} );

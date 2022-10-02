import { useMemo, memo, useState } from '@wordpress/element';
import { ReactSortable } from 'react-sortablejs';
import { getObjectValues } from '../utils';
import { __ } from '@wordpress/i18n';

export default memo( ( props ) => {
	const {
		choices = {},
	} = props.control.params;
	const [ availableItems ] = useState( Object.keys( choices ) );

	const currentAvailableItems = useMemo( () => {
		const savedItems = getObjectValues( props.value ) || [];
		return availableItems.filter( i => -1 === savedItems.indexOf( i ) ).map( i => ( { id: i } ) );
	}, [ props.value ] );

	return (
		<>
			<span className="customize-control-title">{ __( 'Available Components' ) }</span>
			<div className="customind-builder-available-items">
				{ currentAvailableItems.map( ( item ) => (
					<ReactSortable
						ghostClass="customind-builder-item-placeholder"
						chosenClass="customind-builder-item-chosen"
						dragClass="customind-builder-item-dragging"
						key={ item.id }
						className="customind-builder-available-items-list"
						list={ [ item ] }
						setList={ () => {} }
						tag="div"
						group={ { name: `customind-builder-group-${ props.control.id }`, put: 'clone' } }
					>
						<div className="customind-available-item">
							<span className="customind-builder-item-handle">
								<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" aria-hidden="true" focusable="false">
									<path d="M5 4h2V2H5v2zm6-2v2h2V2h-2zm-6 8h2V8H5v2zm6 0h2V8h-2v2zm-6 6h2v-2H5v2zm6 0h2v-2h-2v2z" />
								</svg>
							</span>
							<span className="customind-builder-item-title">
								{ choices?.[ item.id ]?.name || '' }
							</span>
						</div>
					</ReactSortable>
				) ) }
			</div>
		</>
	);
} );

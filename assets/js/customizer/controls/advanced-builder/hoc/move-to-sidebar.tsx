// @ts-nocheck
import {
	BlockControls,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { createHigherOrderComponent } from '@wordpress/compose';
import { useSelect, useDispatch, select as s } from '@wordpress/data';
import { MoveToWidgetArea, getWidgetIdFromBlock } from '@wordpress/widgets';

/**
 * Internal dependencies
 */
import {
	useSidebarControls,
	useActiveSidebarControl,
} from '../hooks/sidebar-controls';
import { useFocusControl } from '../hooks/use-blocks-focus-control';
import { blockToWidget } from '../utils';

const withMoveToSidebarToolbarItem = createHigherOrderComponent(
	( BlockEdit ) => {
		return ( props ) => {
			let widgetId = getWidgetIdFromBlock( props );
			const sidebarControls = useSidebarControls();
			const activeSidebarControl = useActiveSidebarControl();
			const hasMultipleSidebars = sidebarControls?.length > 1;
			const blockName = props.name;
			const clientId = props.clientId;
			const canInsertBlockInSidebar = useSelect(
				( select ) => {
					return select( blockEditorStore ).canInsertBlockType(
						blockName,
						''
					);
				},
				[ blockName ]
			);
			const block = useSelect(
				( select ) => select( blockEditorStore ).getBlock( clientId ),
				[ clientId ]
			);
			const { removeBlock } = useDispatch( blockEditorStore );
			const [ , focusWidget ] = useFocusControl();

			function moveToSidebar( sidebarControlId ) {
				const newSidebarControl = sidebarControls.find(
					( sidebarControl ) => sidebarControl.id === sidebarControlId
				);

				if ( widgetId ) {
					const oldSetting = activeSidebarControl.setting;
					const newSetting = newSidebarControl.setting;

					oldSetting( oldSetting().filter( ( id ) => id !== widgetId ) );
					newSetting( [ ...newSetting(), widgetId ] );
				} else {
					const sidebarAdapter = newSidebarControl.sidebarAdapter;
					removeBlock( clientId );
					const addedWidgetIds = sidebarAdapter.setWidgets( [
						...sidebarAdapter.getWidgets(),
						blockToWidget( block ),
					] );
					widgetId = addedWidgetIds.reverse().find( ( id ) => !! id );
				}
				focusWidget( widgetId );
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ hasMultipleSidebars && canInsertBlockInSidebar && (
						<BlockControls>
							<MoveToWidgetArea
								widgetAreas={ sidebarControls.map(
									( sidebarControl ) => ( {
										id: sidebarControl.id,
										name: sidebarControl.params.label,
										description:
										sidebarControl.params.description,
									} )
								) }
								currentWidgetAreaId={ activeSidebarControl?.id }
								onSelect={ moveToSidebar }
							/>
						</BlockControls>
					) }
				</>
			);
		};
	},
	'withMoveToSidebarToolbarItem'
);

export default withMoveToSidebarToolbarItem;

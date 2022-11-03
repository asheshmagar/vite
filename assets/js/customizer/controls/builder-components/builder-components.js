import { memo, RawHTML, useState, useEffect } from '@wordpress/element';
import { ReactSortable } from 'react-sortablejs';
import { useDeviceSelector } from '../../hooks';
import { sprintf } from '@wordpress/i18n';

export default memo( ( props ) => {
	const {
		control: {
			params: {
				label,
				description,
				inputAttrs: {
					group: id,
				},
				choices,
			},
		},
		customizer,
	} = props;
	let { device } = useDeviceSelector();
	const [ builderItems, setBuilderItems ] = useState( customizer( id ).get() );
	const [ availableItems, setAvailableItems ] = useState( [] );

	device = 'desktop' === device ? 'desktop' : 'mobile';

	useEffect( () => {
		customizer( id ).bind( setBuilderItems );
	}, [] );

	useEffect( () => {
		if ( ! builderItems ) return;
		let items = Object.values( builderItems?.[ device ] ).reduce( ( acc, v ) => {
			for ( const val of Object.values( v ) ) {
				if ( val?.length ) {
					for ( const item of val ) {
						acc = [ ...acc, item.id ];
					}
				}
			}
			return acc;
		}, [] );

		if ( 'mobile' === device ) {
			items = [ ...items, ...( builderItems?.offset ?? [] )?.map( v => v.id, [] ) ];
		}

		let options;

		if ( device in choices ) {
			options = choices[ device ];
		} else {
			options = choices;
		}

		setAvailableItems( Object.entries( options ).map( ( [ item, val ] ) => ( {
			id: item,
			label: val.name,
			section: val.section,
			disabled: items.includes( item ),
		} ) ) );
	}, [ builderItems, device ] );

	return (
		<div className="customize-control customize-builder-components-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<div className="vite-control-body">
				<div className="vite-builder-components">
					{ availableItems.map( ( item ) => (
						<ReactSortable
							forceFallback={ true }
							list={ [ item ] }
							setList={ () => {} }
							key={ item.id }
							className={ `vite-builder-component${ item.disabled ? ' active' : '' }` }
							fallbackClass="vite-builder-component-fallback"
							ghostClass="vite-builder-component-placeholder"
							chosenClass="vite-builder-component-chosen"
							dragClass="vite-builder-component-dragging"
							disabled={ item.disabled }
							animation={ 150 }
							group={ {
								name: id,
								pull: 'clone',
								put: false,
							} }
						>
							{ /* eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions */ }
							<div className="vite-builder-component-inner" onClick={ item.disabled ? () => {
								customizer?.section( item.section )?.focus();
							} : null }>
								<span className="vite-builder-component-title">
									{ item.label }
								</span>
								<span
									className="vite-builder-component-handle"
									dangerouslySetInnerHTML={ {
										__html: sprintf( window?._VITE_CUSTOMIZER_?.icons?.[ item.disabled ? 'chevron-right' : 'arrows-up-down-left-right' ], 'vite-icon', 10, 10 ),
									} }
								/>
							</div>
						</ReactSortable>
					) ) }

				</div>
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

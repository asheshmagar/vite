import { memo, RawHTML, useState, useEffect } from '@wordpress/element';
import { ReactSortable } from 'react-sortablejs';
import { useDeviceSelector } from '../../hooks';
import { sprintf } from '@wordpress/i18n';
import { ControlPropsType } from '../types';
import { noop } from 'lodash';

const BuilderComponents: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			params: {
				label,
				description,
				inputAttrs: {
					group: id,
					context = 'header',
				},
				choices,
			},
		},
		customizer,
	} = props;
	let { device } = useDeviceSelector();
	const [ builderItems, setBuilderItems ] = useState( customizer( id ).get() );
	const [ availableItems, setAvailableItems ] = useState( [] );

	if ( 'header' === context ) {
		device = 'desktop' === device ? 'desktop' : 'mobile';
	} else {
		device = 'desktop';
	}

	useEffect( () => {
		customizer( id ).bind( setBuilderItems );
	}, [] );

	useEffect( () => {
		if ( ! builderItems ) return;
		let items = Object.values( builderItems?.[ device ] ).reduce(
			( acc: {
			[key: string]: any
		}[], v: any ) => {
				for ( const val of Object.values( v ) as any[] ) {
					if ( val?.length ) {
						for ( const item of val ) {
							acc = [ ...acc, item.id ];
						}
					}
				}
				return acc;
			}, [] );

		if ( 'mobile' === device ) {
			items = [ ...items, ...( builderItems?.offset ?? [] )?.map( ( v: any ) => v.id, [] ) ];
		}

		let options: any;

		if ( choices ) {
			if ( device in choices ) {
				options = choices[ device ];
			} else {
				options = choices;
			}
		}

		setAvailableItems( Object.entries( options ).map( ( option: {
			0: string;
			1: any;
		} ) => ( {
			id: option[ 0 ],
			label: option[ 1 ].name,
			section: option[ 1 ].section,
			disabled: items.includes( option[ 0 ] as any ),
		} ) ) as any );
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
					{ availableItems.map( ( item: {
						id: string;
						disabled?: boolean;
						section: string;
						label: string;
					} ) => (
						<ReactSortable
							forceFallback={ true }
							list={ [ item ] }
							// @ts-ignore
							setList={ noop }
							key={ item.id }
							className={ `vite-builder-component${ item?.disabled ? ' active' : '' }` }
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
							} : noop }>
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
				<RawHTML className="customize-control-description">{ description }</RawHTML>
			) }
		</div>
	);
};

export default memo( BuilderComponents );

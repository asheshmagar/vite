import { memo, useState, useEffect, useRef, useCallback, RawHTML } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { Sortable } from 'sortablejs';
import { ReactSortable } from 'react-sortablejs';

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
				},
			},
			setting,
		},
	} = props;

	const [ value, setValue ] = useState( () => {
		const val = setting.get();
		if ( ! val?.length ) {
			return Object.entries( choices ).map( ( [ key, v ] ) => ( {
				id: key,
				label: v,
				visible: false,
			} ) );
		}

		if ( val.some( v => ! v?.id ) ) {
			return val.map( v => ( {
				id: v,
				label: choices[ v ],
				visible: true,
			} ) );
		}

		return val;
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
					animation={ 150 }
					tag="ul"
					className="vite-sortable"
					list={ value }
					setList={ v => {
						if ( JSON.stringify( value ) !== JSON.stringify( v ) ) {
							setting.set( v );
							setValue( v );
						}
					} }
				>
					{ value.map( ( item, idx, arr ) => (
						<li key={ item.id } className="vite-sortable-item">
							<Button
								onClick={ () => {
									const temp = [ ...arr ];
									temp[ idx ] = {
										...temp[ idx ],
										visible: ! temp[ idx ].visible,
									};
									setValue( temp );
									setting.set( temp );
								} }
								iconSize={ 20 }
								icon={ item?.visible ? 'visibility' : 'hidden' }
							/>
							<span>{ item.label }</span>
							{ !! sort && (
								<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
									<path d="M8 7h2V5H8v2zm0 6h2v-2H8v2zm0 6h2v-2H8v2zm6-14v2h2V5h-2zm0 8h2v-2h-2v2zm0 6h2v-2h-2v2z" />
								</svg>
							) }
						</li>
					) ) }
				</ReactSortable>
			</div>
		</div>
	);
} );

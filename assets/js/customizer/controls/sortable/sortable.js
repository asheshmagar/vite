import { memo, useState, useEffect, useRef, useCallback } from '@wordpress/element';
import { Tooltip } from '../../components';
import { Button, Icon } from '@wordpress/components';
import { Sortable } from 'sortablejs';

export default memo( ( props ) => {
	const {
		control: {
			id,
			params: {
				label,
				description,
				choices,
				inputAttrs: {
					unsortable = [],
				},
			},
			setting,
		},
	} = props;

	const sortableRef = useRef();
	const unsortableRef = useRef();
	const [ value ] = useState( setting.get() );

	const update = useCallback( () => {
		const sortableValue = [];
		const unsortableValue = [];
		const items = Array.from( sortableRef.current?.children || [] );
		for ( const item of items ) {
			if ( undefined !== item.dataset.visible ) {
				sortableValue.push( item.dataset.id );
			}
		}
		if ( unsortableRef.current ) {
			const unsortableItems = Array.from( unsortableRef.current?.children || [] );
			for ( const unsortableItem of unsortableItems ) {
				if ( undefined !== unsortableItem.dataset.visible ) {
					unsortableValue.push( unsortableItem.dataset.id );
				}
			}
		}
		const newValue = unsortableValue.concat( sortableValue );
		setting.set( newValue );
	}, [ unsortableRef.current, sortableRef.current ] );

	useEffect( () => {
		if ( ! sortableRef.current ) {
			return;
		}
		Sortable.create( sortableRef.current, {
			animation: 100,
			onEnd() {
				update();
			},
		} );
	}, [] );

	const handleVisibility = ( e ) => {
		e.target.closest( 'li' ).toggleAttribute( 'data-visible' );
		update();
	};

	return (
		<div className="customind-control customind-sortable-control" data-control-id={ id }>
			{ label && (
				<div className="customind-control-head">
					<span className="customize-control-title">{ label }</span>
					{ description && (
						<Tooltip>
							<span className="customize-control-description">{ description }</span>
						</Tooltip>
					) }
				</div>
			) }
			{ 0 < Object.keys( unsortable ?? {} ).length && (
				<ul className="customind-unsortable" ref={ unsortableRef }>
					{ /* eslint-disable-next-line no-shadow */ }
					{ Object.entries( unsortable ).map( ( [ id, name ] ) => {
						if ( ( value || [] ).some( v => v === id ) ) {
							return (
								<li key={ id } data-id={ id } data-visible={ true } className="customind-unsortable-item">
									<Button onClick={ handleVisibility } iconSize={ 20 } icon="visibility" />
									<span>{ name }</span>
								</li>
							);
						}
						return (
							<li key={ id } data-id={ id } className="customind-unsortable-item">
								<Button onClick={ handleVisibility } iconSize={ 20 } icon="visibility" />
								<span>{ name }</span>
							</li>
						);
					} ) }
				</ul>
			) }
			<ul className="customind-sortable" ref={ sortableRef }>
				{ /* eslint-disable-next-line no-shadow */ }
				{ ( value || [] ).map( id => {
					if ( choices?.[ id ] ) {
						return (
							<li key={ id } data-id={ id } data-visible={ true } className="customind-sortable-item">
								<Button onClick={ handleVisibility } iconSize={ 20 } icon="visibility" />
								<span>{ choices[ id ] }</span>
								<Icon
									icon="menu"
									size={ 20 }
								/>
							</li>
						);
					}
					return null;
				} ) }
				{ /* eslint-disable-next-line no-shadow */ }
				{ Object.entries( choices || {} ).map( ( [ id, name ] ) => {
					if ( -1 === ( value || [] ).indexOf( id ) ) {
						return (
							<li key={ id } data-id={ id } className="customind-sortable-item invisible">
								<Button onClick={ handleVisibility } iconSize={ 20 } icon="visibility" />
								<span>{ name }</span>
								<Icon
									icon="menu"
									size={ 20 }
								/>
							</li>
						);
					}
					return null;
				} ) }
			</ul>
		</div>
	);
} );

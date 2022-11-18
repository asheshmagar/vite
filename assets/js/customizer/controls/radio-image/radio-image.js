import { memo, useState, RawHTML, useEffect } from '@wordpress/element';
import { Popover } from '@wordpress/components';
import { noop } from 'lodash';

const Item = ( { onChange, url, label, ...props } ) => {
	const [ anchor, setAnchor ] = useState( null );
	const [ isOpen, setIsOpen ] = useState( false );

	useEffect( () => {
		if ( ! anchor || ! label ) return;

		const listener = () => setIsOpen( prev => ! prev );

		anchor.addEventListener( 'mouseenter', listener );
		anchor.addEventListener( 'mouseleave', listener );
		return () => {
			anchor.removeEventListener( 'mouseenter', listener );
			anchor.removeEventListener( 'mouseleave', listener );
		};
	}, [ anchor ] );

	return (
		<div className="vite-radio-image-item">
			<div
				role="button"
				tabIndex={ -1 }
				onClick={ onChange }
				onKeyDown={ noop }
				{ ...props }
				ref={ setAnchor }
			>
				{ url ? (
					<img style={ { width: '100%' } } src={ url } alt={ label } />
				) : (
					<span>{ label }</span>
				) }
			</div>
			{ ( isOpen && url ) && (
				<Popover className="vite-tooltip" anchor={ anchor } anchorRef={ anchor } position="top center">
					{ label }
				</Popover>
			) }
		</div>
	);
};

export default memo( ( props ) => {
	const {
		control: {
			params: {
				label,
				choices,
				description,
				inputAttrs: {
					col = 2,
				},
			},
			setting,
		},
	} = props;

	const [ value, setValue ] = useState( setting.get() || '' );

	const update = ( val ) => {
		setValue( val );
		setting.set( val );
	};

	return (
		<div className="vite-control vite-radio-image-control" style={ { '--vite--col': col } }>
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
				{ Object.entries( choices ).map( ( [ k, v ] ) => (
					<Item
						key={ k }
						className={ `vite-radio-image${ value === k ? ' active' : '' }` }
						label={ v?.label ?? k }
						url={ v?.image }
						onChange={ () => update( k ) }
					/>
				) ) }
			</div>
		</div>
	);
} );

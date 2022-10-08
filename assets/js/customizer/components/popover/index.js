import { Popover } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { noop } from 'lodash';

export default ( { shouldRender = false, onClose = noop, anchor = null, children } ) => {
	const [ popover, setPopover ] = useState( null );
	useEffect( () => {
		const wrap = document.querySelector( '.wp-full-overlay-sidebar-content' );
		if ( ! anchor || ! wrap ) return;
		const listener = ( e ) => {
			if ( ! anchor?.contains( e.target ) && ! popover?.contains( e.target ) ) {
				if ( shouldRender ) {
					anchor?.click();
					onClose();
				}
			}
		};
		wrap.addEventListener( 'click', listener );
		return () => {
			wrap.removeEventListener( 'click', listener );
		};
	}, [ shouldRender, popover ] );

	if ( ! shouldRender ) {
		return null;
	}

	return (
		<Popover ref={ setPopover } focusOnMount={ false } anchor={ anchor } anchorRef={ anchor } className="vite-popover">
			{ children }
		</Popover>
	);
};

import { useEffect } from '@wordpress/element';

export default ( trigger, popover, callback ) => {
	useEffect( () => {
		const wrapper = document.querySelector( '.wp-full-overlay-sidebar-content' );
		if ( ! popover.current || ! trigger.current || ! wrapper ) return;
		const listener = ( e ) => {
			if ( ! popover.current?.contains( e.target ) && ! trigger.current?.contains( e.target ) ) {
				callback();
			}
		};
		wrapper.addEventListener( 'click', listener );
		return () => {
			wrapper.removeEventListener( 'click', listener );
		};
	}, [] );
};

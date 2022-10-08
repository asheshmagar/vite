import { useEffect, useMemo, useRef } from '@wordpress/element';

export default ( el, isOpen ) => {
	const ref = useRef();

	useEffect( () => {
		const wrap = document.querySelector( '.wp-full-overlay-sidebar-content' );
		if ( ! ref.current || ! el.current || ! wrap ) return;
		const listener = ( e ) => {
			if ( ! ref.current?.contains( e.target ) && ! el.current?.contains( e.target ) ) {
				if ( isOpen ) {
					el.current?.click();
				}
			}
		};
		wrap.addEventListener( 'click', listener );
		return () => {
			wrap.removeEventListener( 'click', listener );
		};
	}, [ isOpen ] );

	return useMemo( () => {
		return ( { children, ...props } ) => (
			<div className="vite-popover" ref={ ref } style={ { display: isOpen ? undefined : 'none' } } { ...props }>
				{ children }
			</div>
		);
	}, [ isOpen ] );
};

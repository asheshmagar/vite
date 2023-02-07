import { useEffect, useMemo, useRef } from '@wordpress/element';

export default ( el: React.MutableRefObject<any>, isOpen: boolean ) => {
	const ref: React.MutableRefObject<any> = useRef();

	useEffect( () => {
		const wrap = document.querySelector( '.wp-full-overlay-sidebar-content' );
		if ( ! ref.current || ! el.current || ! wrap ) return;
		const listener = ( e: Event ) => {
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
		return (
			{ children, ...props }: {
				children: React.ReactNode
			}
		) => (
			<div className="vite-popover" ref={ ref } style={ { display: isOpen ? undefined : 'none' } } { ...props }>
				{ children }
			</div>
		);
	}, [ isOpen ] );
};

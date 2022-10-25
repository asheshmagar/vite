export default ( parent ) => {
	if ( ! parent ) return;
	const focusable = parent.querySelectorAll( 'a, button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])' );
	const firstFocusable = focusable[ 0 ];
	const lastFocusable = focusable[ focusable.length - 1 ];

	parent?.focus();

	parent.addEventListener( 'keydown', ( e ) => {
		const isTabPressed = ( e.key === 'Tab' || e.keyCode === 9 );

		if ( ! isTabPressed ) return;
		if ( e.shiftKey ) {
			if ( document.activeElement === firstFocusable ) {
				lastFocusable.focus();
				e.preventDefault();
			}
		} else if ( document.activeElement === lastFocusable ) {
			firstFocusable.focus();
			e.preventDefault();
		}
	} );
};

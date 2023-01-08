export const $ = document.querySelector.bind( document );

export const $$ = document.querySelectorAll.bind( document );

export const noop = () => {};

export const domReady = ( callback ) => {
	if ( document.readyState === 'complete' || document.readyState === 'interactive' ) {
		callback();
	} else {
		document.addEventListener( 'DOMContentLoaded', callback );
	}
};

export const handleModal = ( {
	modalEl,
	openModalEl,
	closeModalEl,
	onOpen = noop,
	onClose = noop,
} ) => {
	if ( ! modalEl ) return;

	const openModal = ( e ) => {
		e.preventDefault();
		modalEl.classList.add( 'vite-modal--open' );
		modalEl.dataset.modalOpen = '';
		document.body.style.overflow = 'hidden';
		onOpen.call( null, { modalEl, openModalEl, closeModalEl } );
	};

	const closeModal = ( e ) => {
		e.preventDefault();
		modalEl.classList.remove( 'vite-modal--open' );
		document.body.style.overflow = '';
		onClose.call( null, { modalEl, openModalEl, closeModalEl } );
	};

	if ( openModalEl ) {
		if ( openModalEl.length ) {
			openModalEl.forEach( ( el ) => el.addEventListener( 'click', openModal ) );
		} else {
			openModalEl.addEventListener( 'click', openModal );
		}
	}
	closeModalEl?.addEventListener( 'click', closeModal );
};

export const handleTrapFocus = ( parentEl ) => {
	if ( ! parentEl ) return;
	const focusable = parentEl.querySelectorAll( 'a, button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])' );
	const firstFocusable = focusable[ 0 ];
	const lastFocusable = focusable[ focusable.length - 1 ];

	parentEl?.focus();

	parentEl.addEventListener( 'keydown', ( e ) => {
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

export const handleScrollToTop = ( {
	button,
	threshold = 300,
	showButton = noop,
	hideButton = noop,
	scroll = noop,
	showOnLoad = false,
} ) => {
	const showHideButton = () => {
		if ( window.pageYOffset > threshold ) {
			showButton.call( null, { button } );
		} else {
			hideButton.call( null, { button } );
		}
	};

	if ( window.scrollY > threshold && showOnLoad ) showHideButton();

	window.addEventListener( 'scroll', showHideButton );
	button.addEventListener( 'click', ( e ) => {
		e.preventDefault();
		window.scrollTo( {
			top: 0,
			behavior: 'smooth',
		} );
		scroll.call( null, { button } );
	} );
};

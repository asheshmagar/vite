export const $ = document.querySelector.bind( document );

export const $$ = document.querySelectorAll.bind( document );

// eslint-disable-next-line @typescript-eslint/no-empty-function
export const noop: () => void = () => {};

export const domReady = ( callback: () => void ) => {
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
} : {
	modalEl: HTMLElement,
	openModalEl: HTMLElement | NodeListOf<HTMLElement>,
	closeModalEl: HTMLElement,
	onOpen?: ( args: {
		modalEl: HTMLElement,
		closeModalEl: HTMLElement,
		openModalEl: HTMLElement | NodeListOf<HTMLElement>,
	} ) => void,
	onClose?: ( args: {
		modalEl: HTMLElement,
		closeModalEl: HTMLElement,
		openModalEl: HTMLElement | NodeListOf<HTMLElement>,
	} ) => void,
} ) => {
	if ( ! modalEl ) return;

	const openModal = ( e: MouseEvent ) => {
		e.preventDefault();
		modalEl.classList.add( 'vite-modal--open' );
		document.body.style.overflow = 'hidden';
		onOpen( { modalEl, openModalEl, closeModalEl } );
		onOpen.call( null, { modalEl, openModalEl, closeModalEl } );
	};

	const closeModal = ( e: MouseEvent ) => {
		e.preventDefault();
		modalEl.classList.remove( 'vite-modal--open' );
		document.body.style.overflow = '';
		onClose.call( null, { modalEl, openModalEl, closeModalEl } );
	};

	if ( openModalEl ) {
		if ( openModalEl instanceof HTMLElement ) {
			openModalEl.addEventListener( 'click', openModal );
		} else {
			openModalEl.forEach( ( el: HTMLElement ) => el.addEventListener( 'click', openModal ) );
		}
	}
	closeModalEl?.addEventListener( 'click', closeModal );
};

export const handleTrapFocus = ( parentEl: HTMLElement ) => {
	if ( ! parentEl ) return;
	const focusable: NodeListOf<HTMLElement> = parentEl.querySelectorAll( 'a, button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])' );
	const firstFocusable: HTMLElement = focusable[ 0 ];
	const lastFocusable: HTMLElement = focusable[ focusable.length - 1 ];

	parentEl.focus();

	parentEl.addEventListener( 'keydown', ( e: KeyboardEvent ) => {
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
} : {
	button: HTMLButtonElement,
	threshold?: number,
	showButton?: ( args: {
		button: HTMLButtonElement,
	} ) => void,
	hideButton?: ( args: {
		button: HTMLButtonElement,
	} ) => void,
	scroll?: ( args: {
		button: HTMLButtonElement,
	} ) => void,
	showOnLoad?: boolean,
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
	button?.addEventListener( 'click', ( e: MouseEvent ) => {
		e.preventDefault();
		window.scrollTo( {
			top: 0,
			behavior: 'smooth',
		} );
		scroll.call( null, { button } );
	} );
};

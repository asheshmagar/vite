export const $ = document.querySelector.bind( document );

export const $$ = document.querySelectorAll.bind( document );

// eslint-disable-next-line @typescript-eslint/no-empty-function
export const noop: () => void = () => {};

export const domReady: ( args: () => void ) => void = ( callback ) => {
	if ( document.readyState === 'complete' || document.readyState === 'interactive' ) {
		callback();
	} else {
		document.addEventListener( 'DOMContentLoaded', callback );
	}
};

type HandleModalType = ( args: {
	modalEl: HTMLElement,
	openModalEl: HTMLElement | NodeListOf<HTMLElement>,
	closeModalEl: HTMLElement,
	onOpen?: ( a: {
		modalEl: HTMLElement,
		closeModalEl: HTMLElement,
		openModalEl: HTMLElement | NodeListOf<HTMLElement>,
	} ) => void,
	onClose?: ( a: {
		modalEl: HTMLElement,
		closeModalEl: HTMLElement,
		openModalEl: HTMLElement | NodeListOf<HTMLElement>,
	} ) => void,
} ) => void;

export const handleModal: HandleModalType = ( {
	modalEl,
	openModalEl,
	closeModalEl,
	onOpen = noop,
	onClose = noop,
} ) => {
	if ( ! modalEl ) return;

	const openModal = ( e: MouseEvent ) => {
		e.preventDefault();
		modalEl.classList.add( 'vite-modal--open' );
		document.body.style.overflow = 'hidden';
		onOpen( { modalEl, openModalEl, closeModalEl } );
		onOpen.call( null, { modalEl, openModalEl, closeModalEl } );
	};

	const closeModal = ( e: MouseEvent ): void => {
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

export const handleTrapFocus: ( parentEl: HTMLElement, excludeEl?: string[] ) => void = ( parentEl, excludeEl = undefined ) => {
	if ( ! parentEl ) return;
	let focusableElements = [ 'a', 'button', 'input', 'textarea', 'select', 'details', '[tabindex]:not([tabindex="-1"])' ];
	if ( excludeEl ) {
		focusableElements = focusableElements.filter( ( el ) => ! excludeEl.includes( el ) );
	}
	const focusable: NodeListOf<HTMLElement> = parentEl.querySelectorAll( focusableElements.join( ',' ) );
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

type HandleScrollToTopType = ( args: {
	button: HTMLButtonElement,
	threshold?: number,
	showButton?: ( a: {
		button: HTMLButtonElement,
	} ) => void,
	hideButton?: ( a: {
		button: HTMLButtonElement,
	} ) => void,
	scroll?: ( a: {
		button: HTMLButtonElement,
	} ) => void,
	showOnLoad?: boolean,
} ) => void;

export const handleScrollToTop: HandleScrollToTopType = ( {
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
	button?.addEventListener( 'click', ( e: MouseEvent ) => {
		e.preventDefault();
		window.scrollTo( {
			top: 0,
			behavior: 'smooth',
		} );
		scroll.call( null, { button } );
	} );
};

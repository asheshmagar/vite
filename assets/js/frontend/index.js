__webpack_public_path__ = window._VITE_.publicPath; // eslint-disable-line
import { $, $$ } from './utils';

const initScrollToTop = () => {
	const scrollTop = $( '.vite-modal--stt' );
	if ( ! scrollTop ) return;
	import( './scroll-to-top' ).then( ( { default: scrollToTop } ) => {
		scrollToTop( {
			button: scrollTop.querySelector( 'button' ),
			showButton: ( { button } ) => button.closest( '.vite-modal' )?.classList?.add( 'vite-modal--open' ),
			hideButton: ( { button } ) => button.closest( '.vite-modal' )?.classList?.remove( 'vite-modal--open' ),
			showOnLoad: true,
		} );
	} );
};

const initModals = () => {
	const modals = [ ...( $$( '.vite-modal' ) ) ];
	if ( ! modals.length ) return;
	import( './handle-modal' ).then( ( { default: handleModal } ) => {
		// Handle search toggle and modal.
		const searchModal = $( '.vite-modal--search' );
		if ( searchModal ) {
			handleModal( {
				modalEl: searchModal,
				openModalEl: $$( '.vite-search__btn' ),
				closeModalEl: searchModal.querySelector( '.vite-modal__btn' ),
				onOpen: ( { modalEl } ) => {
					const timeout = setTimeout( () => {
						clearTimeout( timeout );
						modalEl.querySelector( '.vite-search-form__input' )?.focus();
						import( './loop-focus' ).then( res => res.default( modalEl ) );
					}, 200 );
				},
				onClose: ( { openModalEl } ) => {
					if ( openModalEl.length ) {
						openModalEl?.[ 1 ]?.focus();
					} else {
						openModalEl?.focus();
					}
				},
			} );
		}
		// Handle mobile menu toggle and modal.
		const mobileMenuModal = $( '.vite-modal--mobile-menu' );
		if ( mobileMenuModal ) {
			handleModal( {
				modalEl: mobileMenuModal,
				openModalEl: $( '.vite-mobile-menu__btn' ),
				closeModalEl: mobileMenuModal.querySelector( '.vite-modal__btn' ),
				onOpen: ( { modalEl, closeModalEl } ) => {
					const timeout = setTimeout( () => {
						clearTimeout( timeout );
						closeModalEl?.focus();
						import( './loop-focus' ).then( res => res.default( modalEl ) );
					}, 100 );
					$( '.vite-modal--mobile-menu' )?.addEventListener( 'click', e => {
						if (
							e.target === modalEl ||
							( e.target?.closest( '.menu' ) && ! e.target?.closest( '.vite-nav__submenu-toggle' ) )
						) {
							closeModalEl?.click();
						}
					} );
				},
				onClose: ( { openModalEl } ) => openModalEl?.focus(),
			} );
		}
	} );
};

const initNavigation = () => {
	const menus = [ ...( $$( '.vite-nav--1, .vite-nav--2' ) ) ];
	if ( menus.length ) {
		menus.forEach( ( menu ) => {
			const links = menu.querySelectorAll( 'a' );
			const innerParent = menu.querySelectorAll( '.vite-nav__item--parent .vite-nav__item--parent' );
			const allParents = menu.querySelectorAll( '.vite-nav__item--parent' );
			const toggles = menu.querySelectorAll( '.vite-nav__submenu-toggle' );

			const toggleFocus = ( e ) => {
				let self = e.target;
				while ( -1 === [ ...self.classList ].findIndex( c => 'vite-nav__list' === c ) ) {
					if ( -1 !== self.className.indexOf( 'focus' ) ) {
						self.className = self.className.replace( ' focus', '' );
					} else {
						self.className += ' focus';
					}
					self = self.parentNode;
				}
			};

			// Add .focus class to parent menu item when a sub-menu link is focused for accessibility.
			for ( const link of links ) {
				link.addEventListener( 'focus', toggleFocus );
				link.addEventListener( 'blur', toggleFocus );
			}

			for ( const toggle of toggles ) {
				toggle?.addEventListener( 'click', ( e ) => {
					const parent = e.target.parentNode;
					if ( parent.classList.contains( 'vite-nav__item--expanded' ) ) {
						toggle.setAttribute( 'aria-expanded', 'false' );
						parent.classList.remove( 'vite-nav__item--expanded' );
					} else {
						toggle.setAttribute( 'aria-expanded', 'true' );
						parent.classList.add( 'vite-nav__item--expanded' );
					}
					e.preventDefault();
				} );
			}

			for ( const parent of allParents ) {
				parent?.addEventListener( 'focusout', ( e ) => {
					if ( ! parent.contains( e.relatedTarget ) ) {
						parent.classList.remove( 'vite-nav__item--expanded' );
						parent.querySelector( '.vite-nav__submenu-toggle' )?.setAttribute( 'aria-expanded', 'false' );
					}
				} );
			}

			for ( const parent of innerParent ) {
				const submenu = parent.querySelector( '.vite-nav__submenu' );
				const icon = parent.querySelector( '.vite-nav__submenu-icon' );
				const rect = submenu.getBoundingClientRect();

				// Auto position the sub-menu.
				if ( rect.right > window.innerWidth ) {
					submenu.classList.add( 'vite-nav__submenu--left' );
					icon.classList.add( 'vite-nav__submenu-icon--left' );
				} else {
					submenu.classList.add( 'vite-nav__submenu--right' );
					icon.classList.add( 'vite-nav__submenu-icon--right' );
				}
			}
		} );
	}

	// Handle mobile submenu toggle.
	const mobileSubmenuToggle = [ ...( $$( '.vite-nav--3 .vite-nav__submenu-toggle' ) ) ];
	if ( mobileSubmenuToggle.length ) {
		mobileSubmenuToggle.forEach( el => {
			const submenu = el.closest( '.menu-item' )?.querySelector( '.vite-nav__submenu' );
			if ( ! submenu ) return;
			const submenuIcon = el.querySelector( '.vite-icon' );
			el.addEventListener( 'click', e => {
				e.preventDefault();
				if ( el.dataset.submenuOpen ) {
					delete el.dataset.submenuOpen;
					submenu.style.display = 'none';
					submenuIcon.style.transform = 'rotate(0deg)';
				} else {
					el.dataset.submenuOpen = 'true';
					submenu.style.display = 'block';
					submenuIcon.style.transform = 'rotate(180deg)';
				}
			} );
		} );
	}
};

const initMasonryInfiniteScroll = () => {
	const masonry = $( '.vite-posts--masonry' );
	const infiniteScroll = $( '.vite-posts--infinite-scroll' );
	const shouldInitInfiniteScroll = infiniteScroll && $( '.vite-pagination__link--next' );

	if ( ! masonry && ! shouldInitInfiniteScroll ) return;

	const initMasonry = ( Masonry ) => {
		return new Masonry( '.vite-posts--masonry', {
			itemSelector: '.vite-post',
			columnWidth: '.vite-post',
		} );
	};

	const initInfiniteScroll = ( InfiniteScroll, outlayer = false ) => {
		return new InfiniteScroll( '.vite-posts--infinite-scroll', {
			path: '.vite-pagination__link--next',
			append: '.vite-post',
			hideNav: '.vite-pagination__links',
			status: '.vite-pagination__status',
			outlayer,
			threshold: 500,
		} );
	};

	if ( masonry && shouldInitInfiniteScroll ) {
		import( 'imagesloaded' ).then( ( { default: imagesLoaded } ) => {
			window.imagesLoaded = imagesLoaded;
			import( 'masonry-layout' ).then( ( { default: Masonry } ) => {
				import( 'infinite-scroll' ).then( ( { default: InfiniteScroll } ) => {
					const masonryInstance = initMasonry( Masonry );
					initInfiniteScroll( InfiniteScroll, masonryInstance );
				} );
			} );
		} );
	}

	if ( masonry && ! shouldInitInfiniteScroll ) {
		import( 'masonry-layout' ).then( ( { default: Masonry } ) => {
			initMasonry( Masonry );
		} );
	}

	if ( ! masonry && shouldInitInfiniteScroll ) {
		import( 'infinite-scroll' ).then( ( { default: InfiniteScroll } ) => {
			initInfiniteScroll( InfiniteScroll );
		} );
	}
};

const init = () => {
	initNavigation();
	initModals();
	initScrollToTop();
	initMasonryInfiniteScroll();
};

if ( ! window._VITE_ ) {
	window._VITE = {};
}

window._VITE_ = {
	...window._VITE_,
	init,
	initNavigation,
	initModals,
	initScrollToTop,
	initMasonryInfiniteScroll,
};

import( './dom-ready' ).then( ( { default: domReady } ) => domReady( init ) );

// @ts-ignore
__webpack_public_path__ = window._VITE_.publicPath; // eslint-disable-line camelcase,no-undef

import {
	$,
	$$,
	handleModal,
	handleScrollToTop,
	handleTrapFocus,
	domReady,
} from './utils';

window._VITE_ = {
	...( window._VITE_ ?? {} ),
	initScrollToTop() {
		const scrollTop = $( '.vite-modal--stt' );
		if ( ! scrollTop?.querySelector( 'button' ) ) return;
		handleScrollToTop( {
			button: <HTMLButtonElement>scrollTop.querySelector( 'button' ),
			showButton( { button } ) {
				button.closest( '.vite-modal' )?.classList?.add( 'vite-modal--open' );
			},
			hideButton( { button } ) {
				button.closest( '.vite-modal' )?.classList?.remove( 'vite-modal--open' );
			},
			showOnLoad: true,
		} );
	},
	initModals() {
		const modals = $$( '.vite-modal' );
		if ( ! modals.length ) return;

		const searchModal = $( '.vite-modal--search' );
		const mobileMenuModal = $( '.vite-modal--mobile-menu' );

		if ( ! searchModal && ! mobileMenuModal ) return;

		if ( searchModal ) {
			// Handle search toggle and modal.
			handleModal( {
				modalEl: <HTMLElement>searchModal,
				openModalEl: <NodeListOf<HTMLElement>>$$( '.vite-search__btn' ),
				closeModalEl: <HTMLElement>searchModal.querySelector( '.vite-modal__btn' ),
				onOpen: ( { modalEl } ) => {
					const timeout = setTimeout( () => {
						clearTimeout( timeout );
						( modalEl.querySelector( '.vite-search-form__input' ) as HTMLElement )?.focus();
						handleTrapFocus( modalEl );
					}, 200 );
				},
				onClose: ( { openModalEl } ) => {
					if ( openModalEl instanceof HTMLElement ) {
						openModalEl.focus();
					} else {
						openModalEl?.[ 1 ]?.focus();
					}
				},
			} );
		}

		// Handle mobile menu toggle and modal.
		if ( mobileMenuModal ) {
			handleModal( {
				modalEl: <HTMLElement>mobileMenuModal,
				openModalEl: <HTMLElement>$( '.vite-mobile-menu__btn' ),
				closeModalEl: <HTMLElement>mobileMenuModal.querySelector( '.vite-modal__btn' ),
				onOpen: ( { modalEl, closeModalEl } ) => {
					const timeout = setTimeout( () => {
						clearTimeout( timeout );
						closeModalEl?.focus();
						handleTrapFocus( modalEl );
					}, 100 );
					( $( '.vite-modal--mobile-menu' ) as HTMLElement )?.addEventListener( 'click', ( {
						target,
					} ) => {
						if ( ! target ) return;
						if (
							target === modalEl ||
							( ( target as HTMLElement )?.closest( '.menu' ) && ! ( target as HTMLElement )?.closest( '.vite-nav__submenu-toggle' ) )
						) {
							closeModalEl?.click();
						}
					} );
				},
				onClose: ( { openModalEl } ) => ( openModalEl as HTMLElement )?.focus(),
			} );
		}
	},
	initNavigation() {
		const menus = <NodeListOf<HTMLElement>>$$( '.vite-nav--1, .vite-nav--2' );
		if ( menus.length ) {
			menus.forEach( ( menu ) => {
				const links: NodeListOf<HTMLAnchorElement> = menu.querySelectorAll( 'a' );
				const innerParent: NodeListOf<HTMLElement> = menu.querySelectorAll( '.vite-nav__item--parent .vite-nav__item--parent' );
				const allParents: NodeListOf<HTMLElement> = menu.querySelectorAll( '.vite-nav__item--parent' );
				const toggles: NodeListOf<HTMLElement> = menu.querySelectorAll( '.vite-nav__submenu-toggle' );

				const toggleFocus = ( e: any ) => {
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
					toggle?.addEventListener( 'click', ( e: any ) => {
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
					parent?.addEventListener( 'focusout', ( e: any ) => {
						if ( ! parent.contains( e.relatedTarget ) ) {
							parent.classList.remove( 'vite-nav__item--expanded' );
							parent.querySelector( '.vite-nav__submenu-toggle' )?.setAttribute( 'aria-expanded', 'false' );
						}
					} );
				}

				for ( const parent of innerParent ) {
					const submenu: Element = <HTMLElement>parent.querySelector( '.vite-nav__submenu' );
					const icon: Element = <HTMLElement>parent.querySelector( '.vite-nav__submenu-icon' );
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
		const mobileSubmenuToggle = <NodeListOf<HTMLElement>>$$( '.vite-nav--3 .vite-nav__submenu-toggle' );
		if ( mobileSubmenuToggle.length ) {
			mobileSubmenuToggle.forEach( ( el: HTMLElement ) => {
				const submenu = <HTMLElement|null>el.closest( '.menu-item' )?.querySelector( '.vite-nav__submenu' );
				if ( ! submenu ) return;
				const submenuIcon = <HTMLElement>el.querySelector( '.vite-icon' );
				el.addEventListener( 'click', ( e: MouseEvent ) => {
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
	},
	initMasonryInfiniteScroll() {
		const masonry = $( '.vite-posts--masonry' );
		const infiniteScroll = $( '.vite-posts--infinite-scroll' );
		const shouldInitInfiniteScroll = infiniteScroll && $( '.vite-pagination__link--next' );

		if ( ! masonry && ! shouldInitInfiniteScroll ) return;

		const initMasonry = ( Masonry: any ) => {
			return new Masonry( '.vite-posts--masonry', {
				itemSelector: '.vite-post',
				columnWidth: '.vite-post',
			} );
		};

		const initInfiniteScroll = ( InfiniteScroll: any, outlayer: any = false ) => {
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
						initInfiniteScroll( InfiniteScroll, initMasonry( Masonry ) );
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
	},
	init() {
		domReady( () => {
			this.initNavigation();
			this.initModals();
			this.initScrollToTop();
			this.initMasonryInfiniteScroll();
		} );
	},
};

window._VITE_.init();

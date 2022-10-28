import domReady from './dom-ready';
import handleModal from './handle-modal';
import loopFocus from './loop-focus';
import { $, $$, slideDown, slideUp } from './utils';

domReady( () => {
	[ ...( $$( '.header-primary-menu, .header-secondary-menu' ) ) ].forEach( ( menu ) => {
		const links = menu.querySelectorAll( 'a' );
		const menuItems = menu.querySelectorAll( '.vite-has-sub-menu' );

		const toggleFocus = ( e ) => {
			let self = e.target;
			while ( -1 === [ ...self.classList ].findIndex( c => [ 'primary-menu', 'secondary-menu' ].includes( c ) ) ) {
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

		for ( const menuItem of menuItems ) {
			const toggle = menuItem.querySelector( '.vite-sub-menu-toggle' );
			const submenu = menuItem.querySelector( '.sub-menu' );
			const rect = submenu.getBoundingClientRect();

			// Auto position the sub-menu.
			if ( rect.right > window.innerWidth ) {
				submenu.dataset.direction = 'left';
				submenu.closest( '.menu-item' ).dataset.direction = 'left';
			} else {
				submenu.dataset.direction = 'right';
				submenu.closest( '.menu-item' ).dataset.direction = 'right';
			}

			// Toggle sub-menu.
			toggle?.addEventListener( 'click', ( e ) => {
				const parent = e.target.parentNode;
				if ( menuItem.dataset.subMenuOpen ) {
					toggle.setAttribute( 'aria-expanded', 'false' );
					delete parent.dataset.subMenuOpen;
				} else {
					parent.dataset.subMenuOpen = 'true';
					toggle.setAttribute( 'aria-expanded', 'true' );
				}
				e.preventDefault();
			} );

			// Collapse sub-menu when focus is out.
			submenu?.addEventListener( 'focusout', ( e ) => {
				if ( ! menuItem.contains( e.relatedTarget ) ) {
					delete menuItem.dataset.subMenuOpen;
					menuItem.querySelector( '.vite-sub-menu-toggle' )?.setAttribute( 'aria-expanded', 'false' );
				}
			} );
		}
	} );

	// Handle mobile submenu toggle.
	[ ...( $$( '.mobile-menu .vite-sub-menu-toggle' ) ) ].forEach( el => {
		const submenu = el.closest( '.menu-item' )?.querySelector( '.sub-menu' );
		if ( ! submenu ) return;
		el.addEventListener( 'click', e => {
			e.preventDefault();
			if ( el.dataset.submenuOpen ) {
				delete el.dataset.submenuOpen;
				slideUp( submenu );
			} else {
				el.dataset.submenuOpen = 'true';
				slideDown( submenu );
			}
		} );
	} );

	// Handle search toggle and modal.
	handleModal( {
		modalEl: $( '.search-modal' ),
		openModalEl: $( '.search-modal-open' ),
		closeModalEl: $( '.search-modal-close' ),
		onOpen: ( { modalEl } ) => {
			const timeout = setTimeout( () => {
				clearTimeout( timeout );
				modalEl.querySelector( '.search-field' )?.focus();
				loopFocus( modalEl );
			}, 100 );
		},
		onClose: ( { openModalEl } ) => openModalEl?.focus(),
	} );

	// Handle mobile menu toggle and modal.
	handleModal( {
		modalEl: $( '.mobile-menu-offset' ),
		openModalEl: $( '.mobile-menu-offset-open' ),
		closeModalEl: $( '.mobile-menu-offset-close' ),
		onOpen: ( { modalEl, closeModalEl } ) => {
			const timeout = setTimeout( () => {
				clearTimeout( timeout );
				closeModalEl?.focus();
				loopFocus( modalEl );
			}, 100 );
			$( '.mobile-menu-offset' )?.addEventListener( 'click', e => {
				if (
					e.target === modalEl ||
					( e.target?.closest( '.menu' ) && ! e.target?.closest( '.vite-sub-menu-toggle' ) )
				) {
					closeModalEl?.click();
				}
			} );
		},
		onClose: ( { openModalEl } ) => openModalEl?.focus(),
	} );
} );

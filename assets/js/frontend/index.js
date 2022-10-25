import domReady from './dom-ready';
import handleModal from './handle-modal';
import loopFocus from './loop-focus';
import { $ } from './shorthand';

domReady( () => {
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
				if ( e.target === modalEl || e.target?.closest( '.menu' ) ) {
					closeModalEl?.click();
				}
			} );
		},
		onClose: ( { openModalEl } ) => openModalEl?.focus(),
	} );
} );

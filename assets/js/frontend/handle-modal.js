import { noop } from './utils';

export default ( {
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

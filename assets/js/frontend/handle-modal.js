const noop = () => {};

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
		modalEl.dataset.modalOpen = '';
		document.body.style.overflow = 'hidden';
		onOpen.call( null, { modalEl, openModalEl, closeModalEl } );
	};

	const closeModal = ( e ) => {
		e.preventDefault();
		delete modalEl.dataset.modalOpen;
		document.body.style.overflow = '';
		onClose.call( null, { modalEl, openModalEl, closeModalEl } );
	};

	openModalEl?.addEventListener( 'click', openModal );
	closeModalEl?.addEventListener( 'click', closeModal );
};

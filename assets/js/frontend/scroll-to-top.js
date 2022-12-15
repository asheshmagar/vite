import { noop } from './utils';

export default ( {
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

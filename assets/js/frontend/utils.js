export const $ = document.querySelector.bind( document );

export const $$ = document.querySelectorAll.bind( document );

export const noop = () => {};

export const slideUp = ( el, duration = 500 ) => {
	el.style.transitionProperty = 'height, margin, padding';
	el.style.transitionDuration = duration + 'ms';
	el.style.boxSizing = 'border-box';
	el.style.height = el.offsetHeight + 'px';
	// eslint-disable-next-line no-unused-expressions
	el.offsetHeight;
	el.style.overflow = 'hidden';
	el.style.height = 0;
	el.style.paddingTop = 0;
	el.style.paddingBottom = 0;
	el.style.marginTop = 0;
	el.style.marginBottom = 0;
	window.setTimeout( () => {
		el.style.display = 'none';
		el.style.removeProperty( 'height' );
		el.style.removeProperty( 'padding-top' );
		el.style.removeProperty( 'padding-bottom' );
		el.style.removeProperty( 'margin-top' );
		el.style.removeProperty( 'margin-bottom' );
		el.style.removeProperty( 'overflow' );
		el.style.removeProperty( 'transition-duration' );
		el.style.removeProperty( 'transition-property' );
	}, duration );
};

export const slideDown = ( el, duration = 500 ) => {
	el.style.removeProperty( 'display' );
	let display = window.getComputedStyle( el ).display;
	if ( display === 'none' ) display = 'block';
	el.style.display = display;
	const height = el.offsetHeight;
	el.style.overflow = 'hidden';
	el.style.height = 0;
	el.style.paddingTop = 0;
	el.style.paddingBottom = 0;
	el.style.marginTop = 0;
	el.style.marginBottom = 0;
	// eslint-disable-next-line no-unused-expressions
	el.offsetHeight;
	el.style.boxSizing = 'border-box';
	el.style.transitionProperty = 'height, margin, padding';
	el.style.transitionDuration = duration + 'ms';
	el.style.height = height + 'px';
	el.style.removeProperty( 'padding-top' );
	el.style.removeProperty( 'padding-bottom' );
	el.style.removeProperty( 'margin-top' );
	el.style.removeProperty( 'margin-bottom' );
	window.setTimeout( () => {
		el.style.removeProperty( 'height' );
		el.style.removeProperty( 'overflow' );
		el.style.removeProperty( 'transition-duration' );
		el.style.removeProperty( 'transition-property' );
	}, duration );
};

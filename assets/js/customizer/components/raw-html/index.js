import { Children, createElement } from '@wordpress/element';

export default ( {
	children,
	tag = 'div',
	...props
} ) => {
	let rawHtml = '';

	Children.toArray( children ).forEach( ( child ) => {
		if ( typeof child === 'string' && child.trim() !== '' ) {
			rawHtml += child;
		}
	} );

	return createElement( tag, {
		dangerouslySetInnerHTML: { __html: rawHtml },
		...props,
	} );
};

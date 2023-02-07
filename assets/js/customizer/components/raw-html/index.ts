import { Children, createElement } from '@wordpress/element';

type PropsType = {
	children: React.ReactNode;
	tag?: string;
	[ key: string ]: any;
}

const RawHTML: React.FC<PropsType> = ( {
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

export default RawHTML;

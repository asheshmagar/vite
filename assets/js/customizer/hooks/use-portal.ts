import { createPortal, unmountComponentAtNode, useCallback, useEffect, useState } from '@wordpress/element';

export default ( element: HTMLElement ) => {
	const [ portal, setPortal ] = useState( {
		render: () => null,
		remove: () => null,
	} );

	const makePortal = useCallback( ( el ) => {
		const Portal = ( { children }: {
			children: React.ReactNode;
		} ) => createPortal( children, el );
		const remove = ( ele: HTMLElement ) => unmountComponentAtNode( ele );
		return {
			render: Portal,
			remove,
		};
	}, [] );

	useEffect( () => {
		if ( element ) portal.remove();
		const newPortal = makePortal( element );
		setPortal( newPortal as any );
		return () => {
			newPortal.remove( element );
		};
	}, [ element ] );

	return portal.render;
};

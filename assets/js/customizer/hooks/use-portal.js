import { createPortal, unmountComponentAtNode, useCallback, useEffect, useState } from '@wordpress/element';

export default ( element ) => {
	const [ portal, setPortal ] = useState( {
		render: () => null,
		remove: () => null,
	} );

	const makePortal = useCallback( ( el ) => {
		const Portal = ( { children } ) => createPortal( children, el );
		const remove = () => unmountComponentAtNode( el );
		return {
			render: Portal,
			remove,
		};
	}, [] );

	useEffect( () => {
		if ( element ) portal.remove();
		const newPortal = makePortal( element );
		setPortal( newPortal );
		return () => {
			newPortal.remove( element );
		};
	}, [ element ] );

	return portal.render;
};

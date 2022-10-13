import Trigger from 'rc-trigger';
import './customizer.scss';
import { useState, useEffect } from '@wordpress/element';

export default ( {
	action = [ 'click' ],
	popupClassName,
	popup = () => null,
	children,
} ) => {
	const [ visible, setVisible ] = useState( false );

	useEffect( () => {
		const section = document.querySelector( '.wp-full-overlay-sidebar-content .accordion-section.open' );

		if ( ! section || ! visible ) return;

		const listener = () => setVisible( false );

		section.addEventListener( 'scroll', listener );

		return () => {
			section.removeEventListener( 'scroll', listener );
		};
	}, [ visible ] );

	return (
		<Trigger
			popupClassName={ popupClassName }
			action={ action }
			prefixCls={ 'vite-popup' }
			getPopupContainer={ () => document.querySelector( '.wp-full-overlay-sidebar' ) }
			popup={ popup }
			zIndex={ 999999 }
			popupAlign={ {
				points: [ 'tl', 'bl' ],
				offset: [ 1, 1 ],
				overflow: {
					adjustX: 1,
					adjustY: 1,
				},
				alwaysByViewport: true,
			} }
			popupVisible={ visible }
			onPopupVisibleChange={ ( a ) => {
				setVisible( a );
			} }
		>
			{ children }
		</Trigger>
	);
};

import Trigger from 'rc-trigger';
import './customizer.scss';
import { useState, useEffect } from '@wordpress/element';

type PropsType = {
	action?: string[];
	popupClassName?: string;
	popup?: () => React.ReactNode;
	children: React.ReactNode;
}

const Popover: React.FC<PropsType> = ( {
	action = [ 'click' ],
	popupClassName,
	popup = () => null,
	children,
} ) => {
	const [ visible, setVisible ] = useState( false );

	useEffect( () => {
		const section: HTMLElement | null = document.querySelector( '.wp-full-overlay-sidebar-content .accordion-section.open' );

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
			// @ts-ignore
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
			} }
			popupVisible={ visible }
			onPopupVisibleChange={ ( a ) => {
				setVisible( a );
			} }
		>
			{ children as React.ReactElement }
		</Trigger>
	);
};

export default Popover;

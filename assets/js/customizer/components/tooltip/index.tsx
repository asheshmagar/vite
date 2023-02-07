import { useEffect, useState, useRef } from '@wordpress/element';
import { Popover, Icon } from '@wordpress/components';
import './customizer.scss';

type PropsType = {
	children: React.ReactNode;
	position?:
		| 'top left'
		| 'top right'
		| 'top center'
		| 'middle left'
		| 'middle right'
		| 'middle center'
		| 'bottom left'
		| 'bottom right'
		| 'bottom center';
	width?: number;
	trigger?: 'hover' | 'click';
	[key: string]: any
}

const ToolTip: React.FC<PropsType> = ( {
	children,
	position = 'bottom center',
	width = 150,
	trigger = 'hover',
	...props
} ) => {
	const [ isOpen, setIsOpen ] = useState( false );
	const ref = useRef<HTMLSpanElement>( null );

	useEffect( () => {
		const el = ref.current;
		if ( ! el ) return;
		if ( 'hover' === trigger ) {
			el?.addEventListener( 'mouseenter', () => setIsOpen( true ) );
			el?.addEventListener( 'mouseleave', () => setIsOpen( false ) );
		} else if ( 'click' === trigger ) {
			el?.addEventListener( 'click', () => setIsOpen( ! isOpen ) );
		}
		return () => {
			if ( 'hover' === trigger ) {
				el?.removeEventListener( 'mouseenter', () => setIsOpen( true ) );
				el?.removeEventListener( 'mouseleave', () => setIsOpen( false ) );
			} else if ( 'click' === trigger ) {
				el?.addEventListener( 'click', () => setIsOpen( ! isOpen ) );
			}
		};
	}, [] );

	return (
		<span ref={ ref } { ...props } className="vite-tooltip" >
			<Icon icon="info-outline" />
			{ isOpen && (
				<Popover
					focusOnMount={ false }
					className="vite-tooltip"
					position={ position }
					onClose={ () => setIsOpen( false ) }
					onFocusOutside={ () => setIsOpen( false ) }
					noArrow={ false }
				>
					<div style={ { minWidth: width + 'px', width: width + 'px', textAlign: 'center' } } className="tgwcfb-tooltip-content">
						{ children }
					</div>
				</Popover>
			) }
		</span>
	);
};

export default ToolTip;

import { ColorPicker, Popover, GradientPicker } from '@wordpress/components';
import { useState, memo, useEffect } from '@wordpress/element';
import './customizer.scss';
import { noop } from 'lodash';

export default memo( ( props ) => {
	const {
		value = '',
		onChange = noop,
		label,
		type = 'color',
	} = props;
	const [ isOpen, setIsOpen ] = useState( false );
	const [ tooltip, setTooltip ] = useState( false );
	const [ anchor, setAnchor ] = useState( null );

	useEffect( () => {
		if ( ! anchor || ! label ) return;

		const listener = () => setTooltip( prev => ! prev );

		anchor.addEventListener( 'mouseenter', listener );
		anchor.addEventListener( 'mouseleave', listener );
		return () => {
			anchor.removeEventListener( 'mouseenter', listener );
			anchor.removeEventListener( 'mouseleave', listener );
		};
	}, [ anchor ] );

	const Picker = 'color' === type ? ColorPicker : GradientPicker;

	let pickerProps = {
		color: value,
		onChangeComplete: val => {
			const { hex, rgb } = val;
			let newColor = hex;
			if ( rgb.a !== 1 ) {
				newColor = `rgba(${ rgb.r },${ rgb.g },${ rgb.b },${ rgb.a })`;
			}
			onChange( newColor );
		},
		enableAlpha: true,
		copyFormat: [ 'hex' ],
	};

	if ( 'color' !== type ) {
		pickerProps = {
			value,
			onChange,
		};
	}

	return (
		<div className="vite-color-picker">
			<span
				ref={ setAnchor }
				style={ {
					height: 24,
					width: 24,
					borderRadius: '50%',
					boxShadow: 'inset 0 0 0 1px rgb(0 0 0 / 20%)',
					display: 'inline-block',
					background: value,
					cursor: 'pointer',
				} }
				onClick={ () => setIsOpen( prev => ! prev ) }
				role="button"
				onKeyDown={ noop }
				tabIndex={ -1 }
			/>
			{ ( label && tooltip ) && (
				<Popover focusOnMount={ false } className="vite-tooltip" position="top center">
					{ label }
				</Popover>
			) }
			{ isOpen && (
				<Popover
					anchor={ anchor }
					anchorRef={ anchor }
					onFocusOutside={ () => setIsOpen( false ) }
					className="vite-popover"
					position="bottom center"
				>
					<Picker
						{ ...pickerProps }
					/>
				</Popover>
			) }
		</div>
	);
} );

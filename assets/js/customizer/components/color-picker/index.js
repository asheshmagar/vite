import { Button, ColorIndicator, ColorPicker, Popover } from '@wordpress/components';
import { useState, memo } from '@wordpress/element';
import './customizer.scss';

export default memo( ( props ) => {
	const {
		value = '',
		onChange = () => {},
		type = 'vite-color',
	} = props;
	const [ isOpen, setIsOpen ] = useState( false );

	return (
		<div className="vite-color-picker">
			<Button onClick={ () => setIsOpen( ! isOpen ) }>
				<ColorIndicator colorValue={ value } />
				<span>Select color</span>
			</Button>
			{ isOpen && (
				'vite-color' === type ? (
					<Popover
						className="vite-color"
						position="bottom center"
						onClose={ () => setIsOpen( false ) }
						onFocusOutside={ () => setIsOpen( false ) }
					>
						<ColorPicker
							color={ value }
							onChangeComplete={ val => {
								const { hex, rgb } = val;
								let newColor = hex;
								if ( rgb.a !== 1 ) {
									newColor = `rgba(${ rgb.r },${ rgb.g },${ rgb.b },${ rgb.a })`;
								}
								onChange( newColor );
							} }
							enableAlpha
							copyFormat={ [ 'hex' ] }
						/>
					</Popover>
				) : (
					<ColorPicker
						color={ value }
						onChangeComplete={ val => {
							const { hex, rgb } = val;
							let newColor = hex;
							if ( rgb.a !== 1 ) {
								newColor = `rgba(${ rgb.r },${ rgb.g },${ rgb.b },${ rgb.a })`;
							}
							onChange( newColor );
						} }
						enableAlpha
						copyFormat={ [ 'hex' ] }
					/>
				)
			) }
		</div>
	);
} );

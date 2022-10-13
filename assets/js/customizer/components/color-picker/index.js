import { ColorPicker, Popover as Tooltip, GradientPicker } from '@wordpress/components';
import { useState, memo } from '@wordpress/element';
import './customizer.scss';
import { Popover } from '../../components';
import { noop } from 'lodash';

export default memo( ( props ) => {
	const {
		value = '',
		onChange = noop,
		label,
		type = 'color',
	} = props;
	const [ tooltip, setTooltip ] = useState( false );

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
			onChange: val => {
				onChange( val );
			},
		};
	}

	return (
		<div className="vite-color-picker">
			<Popover
				popupClassName={ 'vite-color-picker-popup' }
				action={ [ 'click' ] }
				popup={
					<Picker
						{ ...pickerProps }
					/>
				}
			>
				<span>
					<span
						style={ {
							height: 24,
							width: 24,
							borderRadius: '50%',
							boxShadow: 'inset 0 0 0 1px rgb(0 0 0 / 20%)',
							display: 'inline-block',
							background: value,
							cursor: 'pointer',
						} }
						role="button"
						onKeyDown={ noop }
						tabIndex={ -1 }
						onMouseEnter={ () => setTooltip( true ) }
						onMouseLeave={ () => setTooltip( false ) }
					/>
					{ ( label && tooltip ) && (
						<Tooltip focusOnMount={ false } className="vite-tooltip" position="top center">
							{ label }
						</Tooltip>
					) }
				</span>
			</Popover>
		</div>
	);
} );

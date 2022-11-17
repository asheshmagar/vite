import { ColorPicker, Tooltip, GradientPicker, ColorPalette } from '@wordpress/components';
import { memo } from '@wordpress/element';
import './customizer.scss';
import { Popover } from '../../components';
import { noop } from 'lodash';

export default memo( ( props ) => {
	const {
		value = '',
		onChange = noop,
		label,
		type = 'color',
		customizer,
		control,
	} = props;

	const palette = Object.entries( customizer( 'vite[global-palette]' ).get() ?? {} ).map( ( [ key, val ] ) => ( {
		name: key,
		color: `var(${ key })`,
		value: val,
	} ) );

	const variableToColor = ( variable ) => {
		if ( variable?.startsWith( 'var(' ) ) {
			const regex = /var\(([^)]+)\)/;
			const match = variable.match( regex );
			if ( match ) {
				const variableName = match[ 1 ];
				return palette.find( p => p.name === variableName )?.value;
			}
		}
		return variable;
	};
	return (
		<div className="vite-color-picker">
			<Popover
				popupClassName={ 'vite-color-picker-popup' }
				action={ [ 'click' ] }
				popup={
					<>
						{ control.id !== 'vite[global-palette]' && (
							<div className="vite-color-picker-palette">
								<ColorPalette
									onChange={ ( color ) => onChange( color ) }
									value={ value }
									colors={ palette }
									disableCustomColors={ true }
									clearable={ false }
								/>
							</div>
						) }
						{ 'color' === type && (
							<ColorPicker
								color={ variableToColor( value ) }
								onChangeComplete={ ( val ) => {
									const { hex, rgb } = val;
									let newColor = hex;
									if ( rgb.a !== 1 ) {
										newColor = `rgba(${ rgb.r },${ rgb.g },${ rgb.b },${ rgb.a })`;
									}
									onChange( newColor );
								} }
							/>
						) }
						{ 'gradient' === type && (
							<GradientPicker
								value={ value }
								onChange={ ( val ) => onChange( val ) }
							/>
						) }
					</>
				}
			>
				<span style={ { display: 'inline-block' } }>
					<Tooltip
						text={ label }
						delay={ 300 }
						position="top center"
					>
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
						/>
					</Tooltip>
				</span>
			</Popover>
		</div>
	);
} );

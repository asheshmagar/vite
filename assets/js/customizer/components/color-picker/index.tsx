// @ts-ignore
import { ColorPicker, Tooltip, GradientPicker, ColorPalette } from '@wordpress/components';
import { memo, Fragment } from '@wordpress/element';
import './customizer.scss';
import { Popover } from '../../components';
import { noop } from 'lodash';

type PropsType = {
	value?: any;
	onChange?: ( value: any ) => void;
	label?: string;
	type?: 'color'|'gradient';
	customizer?: any;
	control?: any;
}

const LabelWithTooltip: React.FC<{
	title?:string,
	children: React.ReactNode,
}> = ( { title = '', children } ) => {
	let Component = Fragment;
	let componentProps = {};
	if ( '' !== title.trim() ) {
		// @ts-ignore
		Component = Tooltip;
		componentProps = {
			text: title,
			delay: 100,
			position: 'top center',
		};
	}
	return (
		<Component { ...componentProps }>
			{ children }
		</Component>
	);
};

const ViteColorPicker: React.FC<PropsType> = ( props ) => {
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

	const variableToColor = ( variable: string ) => {
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
				popup={ () => {
					return (
						<>
							{ ( control.id !== 'vite[global-palette]' && 'color' === type ) && (
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
									// @ts-ignore
									color={ variableToColor( value ) }
									onChangeComplete={ ( val: any ) => {
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
									onChange={ ( val: any ) => onChange( val ) }
								/>
							) }
						</>
					);
				} }
			>
				<span style={ { display: 'inline-block' } }>
					<LabelWithTooltip title={ label }>
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
					</LabelWithTooltip>
				</span>
			</Popover>
		</div>
	);
};

export default memo( ViteColorPicker );

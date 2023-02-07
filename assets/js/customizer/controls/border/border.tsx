import { RawHTML, memo, useState } from '@wordpress/element';
import { ViteColorPicker, ViteDimensions } from '../../components';
import { Button, SelectControl } from '@wordpress/components';
import { isEqual } from 'lodash';
import { __ } from '@wordpress/i18n';
import { ControlPropsType } from '../types';

const COLOR_STATES = [
	{ label: 'Normal', value: 'normal' },
	{ label: 'Hover', value: 'hover' },
];

const BORDER_STYLES = [
	{ label: 'None', value: 'none' },
	{ label: 'Solid', value: 'solid' },
	{ label: 'Dashed', value: 'dashed' },
	{ label: 'Dotted', value: 'dotted' },
	{ label: 'Double', value: 'double' },
	{ label: 'Groove', value: 'groove' },
	{ label: 'Ridge', value: 'ridge' },
	{ label: 'Inset', value: 'inset' },
	{ label: 'Outset', value: 'outset' },
	{ label: 'Hidden', value: 'hidden' },
];

const Border: React.FC<ControlPropsType> = ( props ) => {
	const {
		control,
		customizer,
		control: {
			setting,
			params: {
				label,
				description,
				default: defaultValue,
				inputAttrs: {
					allow_reset: allowReset = true,
					allow_hover: allowHover = true,
					sides = [ 'top', 'right', 'bottom', 'left' ],
				},
			},
		},
	} = props;

	const [ value, setValue ] = useState( setting.get() ?? {} );

	return (
		<div className="vite-control vite-border-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{ ( ! isEqual( defaultValue, value ) && allowReset ) && (
						<Button
							onClick={ () => {
								setValue( defaultValue );
								setting.set( defaultValue );
							} }
							icon="image-rotate"
							className="vite-reset"
						/>
					) }
				</div>
			) }
			<div className="vite-control-body">
				<div className="vite-border-styles">
					<span>Style</span>
					<SelectControl
						value={ value?.style ?? '' }
						onChange={ val => {
							setValue( ( prev: any ) => {
								prev = { ...( prev || {} ), style: val };
								setting.set( prev );
								return prev;
							} );
						} }
						options={ BORDER_STYLES }
					/>
				</div>
				{ value?.style && 'none' !== value?.style && (
					<>
						<div className="vite-border-colors">
							<span>Color</span>
							<div className="vite-border-colors-inner">
								{ COLOR_STATES.filter( c => ! allowHover ? c.value !== 'hover' : true ).map( s => (
									<ViteColorPicker
										key={ s?.value }
										value={ value?.color?.[ s.value ] ?? '' }
										onChange={ ( color ) => {
											setValue( ( prev: any ) => {
												prev = { ...( prev || {} ),
													color: {
														...( prev?.color || {} ),
														[ s.value ]: color,
													} };
												setting.set( prev );
												return prev;
											} );
										} }
										label={ s.label }
										customizer={ customizer }
										control={ control }
									/>
								) ) }
							</div>
						</div>
						<div className="vite-border-width">
							<span>{ __( 'Width' ) }</span>
							{ /* @ts-ignore */ }
							<ViteDimensions
								onChange={ ( val: any ) => {
									setValue( ( prev: any ) => {
										prev = { ...( prev || {} ), width: {
											...( prev?.width || {} ),
											...val,
										} };
										setting.set( prev );
										return prev;
									} );
								} }
								value={ value?.width ?? {} }
								units={ [ 'px', 'em', 'rem' ] }
								sides={ sides }
							/>
						</div>
					</>
				) }
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
};

export default memo( Border );

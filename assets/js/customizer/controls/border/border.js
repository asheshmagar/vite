import { RawHTML, memo, useState } from '@wordpress/element';
import { ViteColorPicker, ViteRange } from '../../components';
import { SelectControl } from '@wordpress/components';

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

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
			},
		},
	} = props;

	const [ value, setValue ] = useState( setting.get() ?? {} );

	return (
		<div className="vite-control vite-border-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<div className="vite-control-body">
				<div className="vite-border-styles">
					<span>Style</span>
					<SelectControl
						value={ value?.style ?? '' }
						onChange={ val => {
							setValue( prev => {
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
								{ COLOR_STATES.map( s => (
									<ViteColorPicker
										key={ s?.value }
										value={ value?.color?.[ s.value ] ?? '' }
										onChange={ ( color ) => {
											setValue( prev => {
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
									/>
								) ) }
							</div>
						</div>
						<div className="vite-border-width">
							<span>Width</span>
							<ViteRange
								onChange={ val => {
									setValue( prev => {
										prev = { ...( prev || {} ), width: val };
										setting.set( prev );
										return prev;
									} );
								} }
								value={ value?.width ?? '' }
								units={ [ 'px', 'em', 'rem' ] }
								noUnits={ false }
							/>
						</div>
					</>
				) }
				<div className="vite-border-radius">
					<span>Radius</span>
					<ViteRange
						onChange={ val => {
							setValue( prev => {
								prev = { ...( prev || {} ), radius: val };
								setting.set( prev );
							} );
						} }
						value={ value?.radius ?? '' }
						units={ [ 'px', 'em', 'rem', '%' ] }
						noUnits={ false }
					/>
				</div>
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

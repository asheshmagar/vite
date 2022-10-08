import { RawHTML, memo, useState } from '@wordpress/element';
import { CustomindColorPicker, CustomindRange, Reset } from '../../components';
import Select, { Option } from 'rc-select';
import { isEqual } from 'lodash';

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
				default: defaultValue,
			},
		},
	} = props;

	const [ value, setValue ] = useState( setting.get() ?? {} );

	return (
		<div className="vite-control vite-border-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{ ! isEqual( value, defaultValue ) && (
						<Reset
							onClick={ () => {
								setting.set( defaultValue );
								setValue( defaultValue );
							} }
						/>
					) }
				</div>
			) }
			<div className="vite-control-body">
				<div className="vite-border-styles">
					<span>Style</span>
					<Select
						placeholder="Select"
						value={ value?.style ?? '' }
						onChange={ val => {
							const temp = { ...( value || {} ) };
							temp.style = val;
							setValue( temp );
							setting.set( temp );
						} }>
						{ BORDER_STYLES.map( ( b ) => (
							<Option key={ b.value } value={ b.value }>{ b.label }</Option>
						) ) }
					</Select>
				</div>
				{ value?.style && 'none' !== value?.style && (
					<>
						<div className="vite-border-colors">
							<span>Color</span>
							<div className="vite-border-colors-inner">
								{ COLOR_STATES.map( s => (
									<CustomindColorPicker
										key={ s?.value }
										value={ value?.color?.[ s.value ] ?? '' }
										onChange={ ( color ) => {
											const temp = { ...( value || {} ) };
											if ( ! temp?.color ) {
												temp.color = {};
											}
											temp.color[ s.value ] = color;
											setValue( temp );
											setting.set( temp );
										} }
										label={ s.label }
									/>
								) ) }
							</div>
						</div>
						<div className="vite-border-width">
							<span>Width</span>
							<CustomindRange
								onChange={ val => {
									const temp = { ...( value || {} ) };
									temp.width = val;
									setValue( temp );
									setting.set( temp );
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
					<CustomindRange
						onChange={ val => {
							const temp = { ...( value || {} ) };
							temp.radius = val;
							setValue( temp );
							setting.set( temp );
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

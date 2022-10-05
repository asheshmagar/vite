import { memo, useState, useMemo, useCallback } from '@wordpress/element';
import { BaseControl, SelectControl } from '@wordpress/components';
import { useDeviceSelector } from '../../hooks';
import { Tooltip, CustomindRange } from '../../components';
import { __ } from '@wordpress/i18n';
import Select, { Option } from 'rc-select';
import { VARIANTS } from '../../constants';

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				inputAttrs: {
					fonts = [],
					units = {},
					...inputAttrs
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );
	const { device, DeviceSelector } = useDeviceSelector();

	const update = ( val, type = 'font-family' ) => {
		const newValue = { ...value, [ type ]: val };
		setValue( newValue );
		setting.set( newValue );
	};

	const currentFont = useMemo( () => {
		const family = value?.[ 'font-family' ];
		return fonts.filter( g => g.family === family )?.[ 0 ] || {};
	}, [ value ] );

	const inputIcon = () => (
		<svg width="10" height="10" viewBox="0 0 24 24" id="magicoon-Filled" xmlns="http://www.w3.org/2000/svg">
			<path id="chevron-down-Filled-2" data-name="chevron-down-Filled" className="cls-1"
				d="M21.707,8.707l-9,9a1,1,0,0,1-1.414,0l-9-9A1,1,0,1,1,3.707,7.293L12,15.586l8.293-8.293a1,1,0,1,1,1.414,1.414Z" />
		</svg>
	);

	const toWeight = ( variant = '' ) => {
		const matches = variant.match( /(\d+)/ );
		if ( matches ) {
			variant = parseInt( matches[ 0 ] );
		} else {
			variant = 400;
		}
		return variant;
	};

	// const getInputAttrsProps = useCallback( ( type = 'font-size' ) => {
	// 	const unit = value?.[ type ]?.[ device ]?.unit || 'px';
	// 	let min = inputAttrs?.[ device ]?.[ type ]?.min || 16;
	// 	let max = inputAttrs?.[ device ]?.[ type ]?.max || 200;
	// 	let step = inputAttrs?.[ device ]?.[ type ]?.step || 1;
	//
	// 	if ( [ 'em', 'rem' ].includes( unit ) ) {
	// 		min = 0;
	// 		max = 20;
	// 		step = 0.1;
	// 	}
	//
	// 	if ( [ '%', 'vh' ].includes( unit ) ) {
	// 		min = 0;
	// 		max = 100;
	// 		step = 0.1;
	// 	}
	//
	// 	return {
	// 		min,
	// 		max,
	// 		step,
	// 	};
	// }, [ device, value ] );

	const getInputAttrsProps = useCallback( ( type = 'font-size' ) => {
		const allUnits = inputAttrs?.[ device ]?.[ type ];
		if ( ! allUnits ) return {};
		return { ...allUnits };
	}, [ device ] );

	const FontSize = useMemo( () => {
		return () => (
			<CustomindRange
				value={ value?.[ 'font-size' ]?.[ device ] || {} }
				onChange={ val => {
					const newVal = { ...( value?.[ 'font-size' ] || {} ), [ device ]: { ...( value?.[ 'font-size' ]?.[ device ] || {} ), ...val } };
					update( newVal, 'font-size' );
				} }
				units={ units?.[ 'font-size' ] }
				{ ...getInputAttrsProps() }
			/>
		);
	}, [ value, device ] );

	const LetterSpacing = useMemo( () => {
		return () => (
			<CustomindRange
				value={ value?.[ 'letter-spacing' ]?.[ device ] || {} }
				onChange={ val => {
					const newVal = { ...( value?.[ 'letter-spacing' ] || {} ), [ device ]: { ...( value?.[ 'letter-spacing' ]?.[ device ] || {} ), ...val } };
					update( newVal, 'letter-spacing' );
				} }
				units={ units?.[ 'letter-spacing' ] }
				{ ...getInputAttrsProps( 'letter-spacing' ) }
			/>
		);
	}, [ value, device ] );

	const LineHeight = useMemo( () => {
		return () => (
			<CustomindRange
				value={ value?.[ 'line-height' ]?.[ device ] || {} }
				onChange={ val => {
					const newVal = { ...( value?.[ 'line-height' ] || {} ), [ device ]: { ...( value?.[ 'line-height' ]?.[ device ] || {} ), ...val } };
					update( newVal, 'line-height' );
				} }
				units={ units?.[ 'line-height' ] }
				{ ...getInputAttrsProps( 'letter-spacing' ) }
			/>
		);
	}, [ value, device ] );

	return (
		<div className="vite-control vite-typography-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{
						description && (
							<Tooltip>
								<span className="customize-control-description">{ description }</span>
							</Tooltip>
						)
					}
				</div>
			) }
			<div className="vite-control-body">
				<div className="font-family">
					<BaseControl>
						<BaseControl.VisualLabel>{ __( 'Font Family' ) }</BaseControl.VisualLabel>
						<Select value={ value?.[ 'font-family' ] || 'default' } onChange={ val => update( val ) } inputIcon={ inputIcon() } showSearch={ true }>
							<Option value="default">{ __( 'System Default' ) }</Option>
							{ ( fonts || [] ).map( g => (
								<Option key={ g.id } value={ g.family }>{ g.family }</Option>
							) ) }
						</Select>
					</BaseControl>
				</div>
				{ currentFont?.subsets && (
					<div className="subsets">
						<BaseControl>
							<BaseControl.VisualLabel>{ __( 'Subsets' ) }</BaseControl.VisualLabel>
							<Select
								onChange={ val => update( val, 'subsets' ) }
								value={ ( value?.subsets || [] ).filter( s => currentFont.subsets.includes( s ) ) }
								inputIcon={ inputIcon() }
								showSearch={ false }
								mode="multiple"
								showArrow={ true }
							>
								{ currentFont.subsets.map( s => (
									<Option key={ s } value={ s }>{ s }</Option>
								) ) }
							</Select>
						</BaseControl>
					</div>
				) }
			</div>
			{ currentFont?.variants && (
				<div className="font-variant">
					<BaseControl>
						<BaseControl.VisualLabel>{ __( 'Variants' ) }</BaseControl.VisualLabel>
						<Select
							onChange={ val => update( val, 'font-weight' ) }
							value={ VARIANTS?.[ toWeight( value?.[ 'font-weight' ] || currentFont.defVariant ) ] || '' }
							inputIcon={ inputIcon() }
							showSearch={ false }
							showArrow={ true }
						>
							{ currentFont.variants
								.filter( v => -1 === v.indexOf( 'italic' ) )
								.map( v => {
									const weight = toWeight( v );
									return (
										<Option key={ weight } value={ v }>
											{ VARIANTS[ weight ] }
										</Option>
									);
								} ) }
						</Select>
					</BaseControl>
				</div>
			) }
			<div className="font-size">
				<BaseControl>
					<BaseControl.VisualLabel>{ __( 'Size' ) }</BaseControl.VisualLabel>
					<DeviceSelector />
					<div className="font-size-control">
						<FontSize />
					</div>
				</BaseControl>
			</div>
			<div className="line-height">
				<BaseControl>
					<BaseControl.VisualLabel>{ __( 'Line Height' ) }</BaseControl.VisualLabel>
					<DeviceSelector />
					<div className="line-height-control">
						<LineHeight />
					</div>
				</BaseControl>
			</div>
			<div className="letter-spacing">
				<BaseControl>
					<BaseControl.VisualLabel>{ __( 'Letter Spacing' ) }</BaseControl.VisualLabel>
					<DeviceSelector />
					<div className="letter-spacing-control">
						<LetterSpacing />
					</div>
				</BaseControl>
			</div>
			<div className="font-style">
				<SelectControl
					value={ value?.[ 'font-style' ] || 'normal' }
					onChange={ val => update( val, 'font-style' ) }
					label={ __( 'Style' ) }
					options={ [
						{ label: __( 'Normal' ), value: 'normal' },
						{ label: __( 'Italic' ), value: 'italic' },
						{ label: __( 'Oblique' ), value: 'oblique' },
						{ label: __( 'initial' ), value: 'initial' },
						{ label: __( 'Inherit' ), value: 'inherit' },
					] }
				/>
			</div>
			<div className="text-transform">
				<SelectControl
					value={ value?.[ 'text-transform' ] || 'none' }
					onChange={ val => update( val, 'text-transform' ) }
					label={ __( 'Transform' ) }
					options={ [
						{ label: __( 'None' ), value: 'none' },
						{ label: __( 'Capitalize' ), value: 'capitalize' },
						{ label: __( 'Uppercase' ), value: 'uppercase' },
						{ label: __( 'Lowercase' ), value: 'lowercase' },
						{ label: __( 'Initial' ), value: 'initial' },
						{ label: __( 'Inherit' ), value: 'inherit' },
					] }
				/>
			</div>
		</div>
	);
} );

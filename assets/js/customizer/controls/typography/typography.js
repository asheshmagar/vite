import { memo, useState, useMemo, RawHTML } from '@wordpress/element';
import { Popover, SelectControl } from '@wordpress/components';
import { useDeviceSelector } from '../../hooks';
import { ViteRange } from '../../components';
import { __ } from '@wordpress/i18n';
import { VARIANTS } from '../../constants';
import { noop } from 'lodash';
import Select from 'react-select';

const TEXT_TRANSFORMS = [
	{ label: __( 'None', 'vite' ), value: 'none' },
	{ label: __( 'Capitalize', 'vite' ), value: 'capitalize' },
	{ label: __( 'Uppercase', 'vite' ), value: 'uppercase' },
	{ label: __( 'Lowercase', 'vite' ), value: 'lowercase' },
];

const FONT_STYLES = [
	{ label: __( 'Normal', 'vite' ), value: 'normal' },
	{ label: __( 'Italic', 'vite' ), value: 'italic' },
	{ label: __( 'Oblique', 'vite' ), value: 'oblique' },
];

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				fonts,
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );
	const { device, DeviceSelector } = useDeviceSelector();
	const [ anchor, setAnchor ] = useState( null );
	const [ isOpen, setIsOpen ] = useState( false );

	const googleFonts = useMemo( () => {
		return [
			{
				id: 'default',
				family: 'System Default',
				label: 'System Default',
				value: 'System Default',
				variants: [ 'regular', '100', '200', '300', '400', '500', '600', '700', '800', '900' ],
				defVariant: 'regular',
			},
			...( ( fonts ).map( g => ( { ...g, label: g.family, value: g.family } ) ) ),
		];
	}, [] );

	const currentFont = useMemo( () => {
		const family = value?.family ?? 'System Default';
		return googleFonts.find( g => g.family === family ) || {};
	}, [ value ] );

	const toWeight = ( variant = '' ) => {
		const matches = variant.match( /(\d+)/ );
		if ( matches ) {
			variant = parseInt( matches[ 0 ] );
		} else {
			variant = 400;
		}
		return variant;
	};

	return (
		<div className="vite-control vite-typography-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					<span
						role={ 'button' }
						tabIndex={ -1 }
						onKeyDown={ noop }
						style={ { width: 24, height: 24, display: 'grid', placeItems: 'center', cursor: 'pointer', border: '1px solid rgb(117, 117, 117)', borderRadius: 2 } }
						ref={ setAnchor }
						onClick={ () => setIsOpen( prev => ! prev ) }
					>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
							<path d="M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z" />
						</svg>
					</span>
				</div>
			) }
			<div className="vite-control-body">
				{ isOpen && (
					<Popover className="vite-typography-popover" anchorRef={ anchor } anchor={ anchor } position="top right" onFocusOutside={ () => setIsOpen( false ) }>
						<div className="vite-typography">
							<div className="font-family">
								<span>{ __( 'Font Family' ) }</span>
								<Select
									value={ googleFonts.find( g => g.value === ( value?.family ?? 'System Default' ) ) }
									onChange={ val => {
										const temp = { ...value, family: val.value };
										const variants = ( googleFonts.find( g => g.family === val.value )?.variants ?? [] ).map( v => toWeight( v ) );
										if ( value?.weight ) {
											if ( ! variants.includes( value.weight ) ) {
												if ( variants.includes( 400 ) ) {
													temp.weight = 400;
												} else {
													temp.weight = variants[ 0 ];
												}
											}
										}
										setValue( temp );
										setting.set( temp );
									} }
									isSearchable={ true }
									options={ googleFonts }
									classNamePrefix="vite-select"
									className="vite-select"
									components={ {
										IndicatorSeparator: () => null,
										DropdownIndicator: ( { size = 10 } ) => (
											<svg height={ size } width={ size } viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
												<path d="M21.707,8.707l-9,9a1,1,0,0,1-1.414,0l-9-9A1,1,0,1,1,3.707,7.293L12,15.586l8.293-8.293a1,1,0,1,1,1.414,1.414Z" />
											</svg>
										) } }
								/>
							</div>
							{ currentFont?.variants && (
								<div className="font-variant">
									<span>{ __( 'Variants' ) }</span>
									<SelectControl
										onChange={ val => {
											const temp = {
												...value,
												weight: val,
											};
											setValue( temp );
											setting.set( temp );
										} }
										value={ VARIANTS?.[ value?.weight ?? toWeight( currentFont?.defVariant ) ] || '' }
										options={
											currentFont.variants
												.filter( v => -1 === v.indexOf( 'italic' ) )
												.map( v => toWeight( v ) )
												.filter( ( v, i, a ) => a.indexOf( v ) === i )
												.map( v => ( {
													label: VARIANTS[ v ],
													value: v,
												} ) )
										}
									/>
								</div>
							) }
							<div className="font-size">
								<span>{ __( 'Size' ) }</span>
								<DeviceSelector dropdown={ false } />
								<ViteRange
									unitPicker="select"
									value={ value?.size?.[ device ] || '' }
									onChange={ val => {
										const temp = {
											...( value || {} ),
											size: { ...( value?.size || {} ), [ device ]: val },
										};
										setting.set( temp );
										setValue( temp );
									} }
									noUnits={ false }
									min={ 0 }
									max={ 500 }
									step={ 1 }
									units={ [ 'px', 'em', 'rem' ] }
									defaultUnit="px"
								/>
							</div>
							<div className="line-height">
								<span>{ __( 'Line Height' ) }</span>
								<DeviceSelector dropdown={ false } />
								<ViteRange
									unitPicker="select"
									value={ value?.lineHeight?.[ device ] || '' }
									onChange={ val => {
										const temp = {
											...( value || {} ),
											lineHeight: { ...( value?.lineHeight || {} ), [ device ]: val },
										};
										setting.set( temp );
										setValue( temp );
									} }
									noUnits={ false }
									min={ 0 }
									max={ 500 }
									step={ 1 }
									units={ [ '-', 'px', 'em', 'rem' ] }
									defaultUnit="-"
								/>
							</div>
							<div className="letter-spacing">
								<span>{ __( 'Letter Spacing' ) }</span>
								<DeviceSelector dropdown={ false } />
								<ViteRange
									unitPicker="select"
									value={ value?.letterSpacing?.[ device ] || '' }
									onChange={ val => {
										const temp = {
											...( value || {} ),
											letterSpacing: { ...( value?.letterSpacing || {} ), [ device ]: val },
										};
										setting.set( temp );
										setValue( temp );
									} }
									noUnits={ false }
									min={ 0 }
									max={ 500 }
									step={ 1 }
									units={ [ 'px', 'em', 'rem' ] }
									defaultUnit="em"
								/>
							</div>
							<div className="font-style">
								<span>{ __( 'Style' ) }</span>
								<SelectControl
									onChange={ val => {
										const temp = {
											...( value || {} ),
											style: val,
										};
										setting.set( temp );
										setValue( temp );
									} }
									value={ value?.style || 'normal' }
									options={ FONT_STYLES }
								/>
							</div>
							<div className="text-transform">
								<span>{ __( 'Transform' ) }</span>
								<SelectControl
									value={ value?.transform || 'none' }
									onChange={ val => {
										const temp = {
											...( value || {} ),
											transform: val,
										};
										setting.set( temp );
										setValue( temp );
									} }
									options={ TEXT_TRANSFORMS }
								/>
							</div>

						</div>
					</Popover>
				) }
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

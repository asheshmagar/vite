import { memo, useState, useMemo, RawHTML } from '@wordpress/element';
import { Popover } from '@wordpress/components';
import { useDeviceSelector } from '../../hooks';
import { CustomindRange } from '../../components';
import { __ } from '@wordpress/i18n';
import Select, { Option } from 'rc-select';
import { VARIANTS } from '../../constants';
import { dropdownIcon } from '../../utils';
import { noop } from 'lodash';

const GOOGLE_FONTS = _VITE_CUSTOMIZER_.googleFonts;

GOOGLE_FONTS.unshift( {
	id: 'default',
	family: 'System Default',
	variants: [ 'regular', '100', '200', '300', '400', '500', '600', '700', '800', '900' ],
	defVariant: 'regular',
} );

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
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );
	const { device, DeviceSelector } = useDeviceSelector();
	const [ anchor, setAnchor ] = useState( null );
	const [ isOpen, setIsOpen ] = useState( false );

	const currentFont = useMemo( () => {
		const family = value?.family ?? 'System Default';
		return GOOGLE_FONTS.filter( g => g.family === family )?.[ 0 ] || {};
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
					<Popover className="vite-typography-popover" anchorRef={ anchor } anchor={ anchor } position="bottom right" onFocusOutside={ () => setIsOpen( false ) }>
						<div className="vite-typography">
							<div className="font-family">
								<span>{ __( 'Font Family' ) }</span>
								<Select
									value={ value?.family || 'System Default' }
									onChange={ val => {
										const temp = { ...value, family: val };
										const variants = ( GOOGLE_FONTS.find( g => g.family === val )?.variants ?? [] ).map( v => toWeight( v ) );
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
									inputIcon={ dropdownIcon }
									showSearch={ true }
								>
									{ ( GOOGLE_FONTS || [] ).map( g => (
										<Option key={ g.id } value={ g.family }>{ g.family }</Option>
									) ) }
								</Select>
							</div>
							{ currentFont?.variants && (
								<div className="font-variant">
									<span>{ __( 'Variants' ) }</span>
									<Select
										onChange={ val => {
											const temp = {
												...value,
												weight: val,
											};
											setValue( temp );
											setting.set( temp );
										} }
										value={ VARIANTS?.[ value?.weight ?? toWeight( currentFont?.defVariant ) ] || '' }
										inputIcon={ dropdownIcon }
										showSearch={ false }
										showArrow={ true }
									>
										{ currentFont.variants
											.filter( v => -1 === v.indexOf( 'italic' ) )
											.map( v => toWeight( v ) )
											.filter( ( v, i, a ) => a.indexOf( v ) === i )
											.map( v => {
												return (
													<Option key={ v } value={ v }>
														{ VARIANTS[ v ] }
													</Option>
												);
											} ) }
									</Select>
								</div>
							) }
							<div className="font-size">
								<span>{ __( 'Size' ) }</span>
								<DeviceSelector dropdown={ false } />
								<CustomindRange
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
								<CustomindRange
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
								<CustomindRange
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
								<Select
									inputIcon={ dropdownIcon }
									onChange={ val => {
										const temp = {
											...( value || {} ),
											style: val,
										};
										setting.set( temp );
										setValue( temp );
									} }
									value={ value?.style || 'normal' }
								>
									{ FONT_STYLES.map( s => (
										<Option key={ s.value } value={ s.value }>{ s.label }</Option>
									) ) }
								</Select>
							</div>
							<div className="text-transform">
								<span>{ __( 'Transform' ) }</span>
								<Select
									inputIcon={ dropdownIcon }
									value={ value?.transform || 'none' }
									onChange={ val => {
										const temp = {
											...( value || {} ),
											transform: val,
										};
										setting.set( temp );
										setValue( temp );
									} }
								>
									{ TEXT_TRANSFORMS.map( t => (
										<Option key={ t.value } value={ t.value }>{ t.label }</Option>
									) ) }
								</Select>
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

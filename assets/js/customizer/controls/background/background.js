import { memo, useState, RawHTML } from '@wordpress/element';
import { MediaUpload } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { ViteColorPicker } from '../../components';
import { ButtonGroup, FocalPointPicker, SelectControl } from '@wordpress/components';
import { useDeviceSelector } from '../../hooks';
import Tab from './tab';

const TABS = [
	{ label: 'Color', value: 'color', icon: 'admin-customizer' },
	{ label: 'Gradient', value: 'gradient', icon: 'art' },
	{ label: 'Image', value: 'image', icon: 'format-image' },
];

const REPEATS = [
	{ label: 'No Repeat', value: 'no-repeat' },
	{ label: 'Repeat', value: 'repeat' },
	{ label: 'Repeat X', value: 'repeat-x' },
	{ label: 'Repeat Y', value: 'repeat-y' },
];

const SIZES = [
	{ label: 'Auto', value: 'auto' },
	{ label: 'Cover', value: 'cover' },
	{ label: 'Contain', value: 'contain' },
];

const ATTACHMENTS = [
	{ label: 'Scroll', value: 'scroll' },
	{ label: 'Fixed', value: 'fixed' },
];

export default memo( ( props ) => {
	const {
		control,
		control: {
			setting,
			params: {
				label,
				description,
			},
		},
		customizer,
	} = props;
	const [ value, setValue ] = useState( setting.get() );
	const { device, DeviceSelector } = useDeviceSelector();

	let position = {
		x: 0.5,
		y: 0.5,
	};

	if ( value?.position?.[ device ] ) {
		const temp = value.position[ device ].replaceAll( '%', '' ).split( ' ' );
		position = {
			x: parseFloat( ( parseFloat( temp[ 0 ] ) / 100 ).toString() ),
			y: parseFloat( ( parseFloat( temp[ 1 ] ) / 100 ).toString() ),
		};
	}

	return (
		<div className="vite-control vite-background-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<ButtonGroup className="vite-background-tabs">
				{ TABS.map( tab => (
					<Tab
						key={ tab.value }
						className={ `vite-${ tab.value }${ value?.type === tab.value || ( ! value?.type && 'color' === tab.value ) ? ' is-primary' : '' }` }
						onClick={ () => {
							const temp = { ...( value || {} ) };
							temp.type = tab.value;
							setting.set( temp );
							setValue( temp );
						} }
						icon={ tab.icon }
						label={ tab.label }
					/>
				) ) }
			</ButtonGroup>
			<div className="vite-control-body">
				{ ( ! value?.type || value?.type === 'color' ) && (
					<ViteColorPicker
						value={ value?.color || '' }
						onChange={ val => {
							const temp = {
								...( value || {} ),
								color: val,
							};
							setting.set( temp );
							setValue( temp );
						} }
						customizer={ customizer }
						control={ control }
					/>
				) }
				{ value?.type === 'gradient' && (
					<ViteColorPicker
						value={ value?.gradient || '' }
						onChange={ val => {
							const temp = { ...( value || {} ), gradient: val };
							setting.set( temp );
							setValue( temp );
						} }
						type="gradient"
						customizer={ customizer }
						control={ control }
					/>
				) }

				{ value?.type === 'image' && (
					<MediaUpload
						onSelect={ imgData => {
							const newVal = { ...value, image: imgData.url };
							setValue( newVal );
							setting.set( newVal );
						} }
						allowedTypes={ [ 'image' ] }
						render={ ( { open } ) => (
							<div className="attachment-media-view">
								{ value?.image ? (
									<>
										<DeviceSelector />
										<FocalPointPicker
											url={ value.image }
											value={ position }
											onChange={ ( { x, y } ) => {
												const temp = {
													...( value || {} ),
													position: {
														...( value?.position || {} ),
														[ device ]: `${ ( parseFloat( x.toString() ) * 100 ).toFixed( 2 ) }% ${ ( parseFloat( y.toString() ) * 100 ).toFixed( 2 ) }%`,
													},
												};
												setting.set( temp );
												setValue( temp );
											} }
										/>
										<div className="actions">
											<button onClick={ e => {
												e.preventDefault();
												open();
											} } className="button">
												{ __( 'Change Image' ) }
											</button>
											<button
												onClick={ ( e ) => {
													e.preventDefault();
													const newVal = { ...value, image: undefined };
													setValue( newVal );
													setting.set( newVal );
												} }
												className="button"
											>
												{ __( 'Remove Image' ) }
											</button>
										</div>
										<div className="vite-background-repeat">
											<span>{ __( 'Background Repeat' ) }</span>
											<DeviceSelector />
											<SelectControl
												className="vite-select"
												onChange={ val => {
													const temp = {
														...( value || {} ),
														repeat: {
															...( value?.repeat || {} ),
															[ device ]: val,
														},
													};
													setting.set( temp );
													setValue( temp );
												} }
												value={ value?.repeat?.[ device ] ?? 'repeat' }
												options={ REPEATS }
											/>
										</div>
										<div className="vite-background-size">
											<span>{ __( 'Background Size' ) }</span>
											<DeviceSelector />
											<SelectControl
												className="vite-select"
												onChange={ val => {
													const temp = {
														...( value || {} ),
														size: {
															...( value?.size || {} ),
															[ device ]: val,
														},
													};
													setting.set( temp );
													setValue( temp );
												} }
												value={ value?.size?.[ device ] ?? 'auto' }
												options={ SIZES }
											/>
										</div>
										<div className="vite-background-attachment">
											<span>{ __( 'Background Attachment' ) }</span>
											<DeviceSelector />
											<SelectControl
												className="vite-select"
												onChange={ val => {
													const temp = {
														...( value || {} ),
														attachment: {
															...( value?.attachment || {} ),
															[ device ]: val,
														},
													};
													setting.set( temp );
													setValue( temp );
												} }
												value={ value?.attachment?.[ device ] ?? 'scroll' }
												options={ ATTACHMENTS }
											/>
										</div>
									</>
								) : (
									<button type="button" onClick={ open } className="upload-button button-add-media">
										{ __( 'Select Image' ) }
									</button>
								) }
							</div>
						) }
					/>
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

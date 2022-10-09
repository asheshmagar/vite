import { memo, useState, RawHTML } from '@wordpress/element';
import { MediaUpload } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { CustomindColorPicker } from '../../components';
import { ButtonGroup, Button, FocalPointPicker } from '@wordpress/components';
import { useDeviceSelector } from '../../hooks';
import Select, { Option } from 'rc-select';

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
					<Button
						key={ tab.value }
						className={ value?.type === tab.value || ( ! value?.type && 'color' === tab.value ) ? 'is-primary' : '' }
						onClick={ () => {
							const temp = { ...( value || {} ) };
							temp.type = tab.value;
							setting.set( temp );
							setValue( temp );
						} }
						icon={ tab.icon }
					/>
				) ) }
			</ButtonGroup>
			<div className="vite-control-body">
				{ ( ! value?.type || value?.type === 'color' ) && (
					<CustomindColorPicker
						value={ value?.color || '' }
						onChange={ val => {
							const temp = {
								...( value || {} ),
								color: val,
							};
							setting.set( temp );
							setValue( temp );
						} }
					/>
				) }
				{ value?.type === 'gradient' && (
					<CustomindColorPicker
						value={ value?.gradient || '' }
						onChange={ val => {
							const temp = { ...( value || {} ), gradient: val };
							setting.set( temp );
							setValue( temp );
						} }
						type="gradient"
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
											<Select
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
											>
												{ REPEATS.map( repeat => (
													<Option value={ repeat.value } key={ repeat.value }>{ repeat.label }</Option>
												) ) }
											</Select>
										</div>
										<div className="vite-background-size">
											<span>{ __( 'Background Size' ) }</span>
											<DeviceSelector />
											<Select
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
											>
												{ SIZES.map( size => (
													<Option value={ size.value } key={ size.value }>{ size.label }</Option>
												) ) }
											</Select>
										</div>
										<div className="vite-background-attachment">
											<span>{ __( 'Background Attachment' ) }</span>
											<DeviceSelector />
											<Select
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
											>
												{ ATTACHMENTS.map( attachment => (
													<Option value={ attachment.value } key={ attachment.value }>{ attachment.label }</Option>
												) ) }
											</Select>
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

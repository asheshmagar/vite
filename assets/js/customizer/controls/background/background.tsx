import { memo, useState, RawHTML } from '@wordpress/element';
import { MediaUpload } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { ViteColorPicker } from '../../components';
import { Button, ButtonGroup, FocalPointPicker, SelectControl, Tooltip } from '@wordpress/components';
import { useDeviceSelector } from '../../hooks';
import { isEqual } from 'lodash';
import { ControlPropsType } from '../types';

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

const Background: React.FC<ControlPropsType> = ( props ) => {
	const {
		control,
		control: {
			setting,
			params: {
				label,
				description,
				default: defaultValue,
				inputAttrs: {
					allow_reset: allowReset = true,
				},
			},
		},
		customizer,
	} = props;
	const [ value, setValue ] = useState( setting.get() || {} );
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
			<ButtonGroup className="vite-background-tabs">
				{ TABS.map( tab => (
					<Tooltip
						key={ tab.value }
						text={ tab.label ?? '' }
						// @ts-ignore
						delay={ 100 }
						position="top center"
					>
						<Button
							className={ `vite-${ tab.value }${ value?.type === tab.value || ( ! value?.type && 'color' === tab.value ) ? ' is-primary' : '' }` }
							onClick={ () => {
								setValue( ( prev: any ) => {
									prev = {
										...( prev ?? {} ),
										type: tab.value,
									};
									setting.set( prev );
									return prev;
								} );
							} }
							icon={ tab.icon }
						/>
					</Tooltip>
				) ) }
			</ButtonGroup>
			<div className="vite-control-body">
				{ ( ! value?.type || value?.type === 'color' ) && (
					<ViteColorPicker
						value={ value?.color || '' }
						onChange={ val => {
							setValue( ( prev: any ) => {
								prev = {
									...( prev ?? {} ),
									color: val,
								};
								setting.set( prev );
								return prev;
							} );
						} }
						customizer={ customizer }
						control={ control }
					/>
				) }
				{ value?.type === 'gradient' && (
					<ViteColorPicker
						value={ value?.gradient || '' }
						onChange={ val => {
							setValue( ( prev: any ) => {
								prev = {
									...( prev ?? {} ),
									gradient: val,
								};
								setting.set( prev );
								return prev;
							} );
						} }
						type="gradient"
						customizer={ customizer }
						control={ control }
					/>
				) }
				{ value?.type === 'image' && (
					<MediaUpload
						onSelect={ imgData => {
							setValue( ( prev: any ) => {
								prev = {
									...( prev ?? {} ),
									image: imgData.url,
								};
								setting.set( prev );
								return prev;
							} );
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
												setValue( ( prev: any ) => {
													prev = {
														...( prev ?? {} ),
														position: {
															...( prev?.position ?? {} ),
															[ device ]: `${ ( parseFloat( x.toString() ) * 100 ).toFixed( 2 ) }% ${ ( parseFloat( y.toString() ) * 100 ).toFixed( 2 ) }%`,
														},
													};
													setting.set( prev );
													return prev;
												} );
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
													setValue( ( prev: any ) => {
														prev = {
															...( prev ?? {} ),
															image: undefined,
														};
														setting.set( prev );
														return prev;
													} );
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
													setValue( ( prev: any ) => {
														prev = {
															...( prev ?? {} ),
															repeat: {
																...( prev?.repeat ?? {} ),
																[ device ]: val,
															},
														};
														setting.set( prev );
														return prev;
													} );
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
													setValue( ( prev: any ) => {
														prev = {
															...( prev ?? {} ),
															size: {
																...( prev?.size ?? {} ),
																[ device ]: val,
															},
														};
														setting.set( prev );
														return prev;
													} );
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
													setValue( ( prev: any ) => {
														prev = {
															...( prev ?? {} ),
															attachment: {
																...( prev?.attachment ?? {} ),
																[ device ]: val,
															},
														};
														setting.set( prev );
														return prev;
													} );
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
				<RawHTML className="customize-control-description">{ description }</RawHTML>
			) }
		</div>
	);
};

export default memo( Background );

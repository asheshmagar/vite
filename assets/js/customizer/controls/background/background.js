import { memo, useState } from '@wordpress/element';
// eslint-disable-next-line import/named
import { MediaUpload } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { Tooltip, CustomindColorPicker } from '../../components';
import { SelectControl } from '@wordpress/components';

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

	const repeatOptions = [
		{ label: __( 'No Repeat' ), value: 'no-repeat' },
		{ label: __( 'Repeat All' ), value: 'repeat' },
		{ label: __( 'Repeat Horizontally' ), value: 'repeat-x' },
		{ label: __( 'Repeat Vertically' ), value: 'repeat-y' },
	];

	const positionOptions = [
		{ label: __( 'Left Top' ), value: 'left top' },
		{ label: __( 'Left Center' ), value: 'left center' },
		{ label: __( 'Left Bottom' ), value: 'left bottom' },
		{ label: __( 'Right Top' ), value: 'right top' },
		{ label: __( 'Right Center' ), value: 'right center' },
		{ label: __( 'Right Bottom' ), value: 'right bottom' },
		{ label: __( 'Center Top' ), value: 'center top' },
		{ label: __( 'Center Center' ), value: 'center center' },
		{ label: __( 'Center Bottom' ), value: 'center bottom' },
	];

	const sizeOptions = [
		{ label: __( 'Cover' ), value: 'cover' },
		{ label: __( 'Contain' ), value: 'contain' },
		{ label: __( 'Auto' ), value: 'auto' },
	];

	const attachmentOptions = [
		{ label: __( 'Scroll' ), value: 'scroll' },
		{ label: __( 'Fixed' ), value: 'fixed' },
	];
	return (
		<div className="customind-control customind-background-control">
			{ label && (
				<div className="customind-control-head">
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
			<div className="customind-control-body">
				<CustomindColorPicker value={ value?.[ 'background-color' ] || '' } onChange={ color => {
					const newVal = { ...value, 'background-color': color };
					setValue( newVal );
					setting.set( newVal );
				} } />
				<MediaUpload
					onSelect={ imgData => {
						const newVal = { ...value, 'background-image': imgData.url };
						setValue( newVal );
						setting.set( newVal );
					} }
					allowedTypes={ [ 'image' ] }
					render={ ( { open } ) => (
						<div className="attachment-media-view">
							{ value?.[ 'background-image' ] ? (
								<div role="button" tabIndex={ 0 } onClick={ open } onKeyDown={ e => e.code === 'Enter' && open() } className="thumbnail thumbnail-image">
									<img src={ value[ 'background-image' ] } alt="Preview" />
								</div>
							) : (
								<button type="button" onClick={ open } className="upload-button button-add-media">
									{ __( 'Select Image' ) }
								</button>
							) }

							{ value?.[ 'background-image' ] && (
								<div className="actions">
									<button onClick={ open } className="button">
										{ __( 'Change Image' ) }
									</button>
									<button
										onClick={ () => {
											const newVal = { ...value, 'background-image': '' };
											setValue( newVal );
											setting.set( newVal );
										} }
										className="button"
									>
										{ __( 'Remove Image' ) }
									</button>
								</div>
							) }
						</div>
					) }
				/>
				{ value?.[ 'background-image' ] && (
					<>
						<SelectControl
							value={ value?.[ 'background-repeat' ] || 'repeat' }
							onChange={ val => {
								const newVal = { ...value, 'background-repeat': val };
								setValue( newVal );
								setting.set( newVal );
							} }
							label={ __( 'Background Repeat' ) }
							options={ repeatOptions }
						/>
						<SelectControl
							value={ value?.[ 'background-position' ] || 'ce' }
							onChange={ val => {
								const newVal = { ...value, 'background-repeat': val };
								setValue( newVal );
								setting.set( newVal );
							} }
							label={ __( 'Background Position' ) }
							options={ positionOptions }
						/>
						<SelectControl
							value={ value?.[ 'background-size' ] || 'auto' }
							onChange={ val => {
								const newVal = { ...value, 'background-size': val };
								setValue( newVal );
								setting.set( newVal );
							} }
							label={ __( 'Background Size' ) }
							options={ sizeOptions }
						/>
						<SelectControl
							value={ value?.[ 'background-attachment' ] || 'scroll' }
							onChange={ val => {
								const newVal = { ...value, 'background-attachment': val };
								setValue( newVal );
								setting.set( newVal );
							} }
							label={ __( 'Background Attachment' ) }
							options={ attachmentOptions }
						/>
					</>
				) }
			</div>
		</div>
	);
} );

import { memo, useState, RawHTML } from '@wordpress/element';
import { ViteColorPicker } from '../../components';
import { isEqual } from 'lodash';
import { Button } from '@wordpress/components';

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				inputAttrs,
				default: defaultValue,
				inputAttrs: {
					allow_reset: allowReset = true,
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	const multiple = inputAttrs?.colors;

	return (
		<div className="vite-control vite-color-control" data-inline={ inputAttrs.colors?.length <= 4 }>
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
				{ multiple ? (
					inputAttrs.colors.map( c => (
						<ViteColorPicker
							key={ c?.id }
							value={ value?.[ c?.id ] ?? '' }
							onChange={ ( color ) => {
								const temp = { ...( value || {} ) };
								temp[ c?.id ] = color;
								setValue( temp );
								setting.set( temp );
							} }
							label={ c?.label }
							{ ...props }
						/>
					) )
				) : (
					<ViteColorPicker
						value={ value }
						onChange={ ( color ) => {
							setValue( color );
							setting.set( color );
						} }
						{ ...props }
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

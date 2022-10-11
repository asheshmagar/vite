import { memo, useState, RawHTML } from '@wordpress/element';
import { CustomindColorPicker } from '../../components';

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				inputAttrs,
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	const multiple = inputAttrs?.colors;

	return (
		<div className="vite-control vite-color-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<div className="vite-control-body">
				{ multiple ? (
					inputAttrs.colors.map( c => (
						<CustomindColorPicker
							key={ c?.id }
							value={ value?.[ c?.id ] ?? '' }
							onChange={ ( color ) => {
								const temp = { ...( value || {} ) };
								temp[ c?.id ] = color;
								setValue( temp );
								setting.set( temp );
							} }
							label={ c?.label }
						/>
					) )
				) : (
					<CustomindColorPicker value={ value } onChange={ ( color ) => {
						setValue( color );
						setting.set( color );
					} } />
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

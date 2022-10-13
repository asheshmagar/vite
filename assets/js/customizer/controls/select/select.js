import { memo, useState, RawHTML } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';

export default memo( ( props ) => {
	let {
		control: {
			setting,
			params: {
				label,
				description,
				choices = {},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	if ( ! Object.keys( choices )?.length ) {
		return null;
	}

	choices = Object.keys( choices ).map( c => ( {
		value: c,
		label: choices[ c ],
	} ) );

	return (
		<div className="vite-control vite-select-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<div className="vite-control-body">
				<SelectControl
					value={ value }
					onChange={ ( val ) => {
						setValue( val );
						setting.set( val );
					} }
					options={ choices }
					className="vite-select"
				/>
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

import { RawHTML, useState, memo } from '@wordpress/element';
import { RadioControl } from '@wordpress/components';

export default memo( ( props ) => {
	const {
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

	return (
		<div className="vite-control vite-radio-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<div className="vite-control-body">
				<RadioControl
					onChange={ ( val ) => {
						setValue( val );
						setting.set( val );
					} }
					selected={ value }
					options={ Object.entries( choices ).map( ( [ key, val ] ) => ( { label: val, value: key } ) ) }
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

import { memo, useState, RawHTML } from '@wordpress/element';
import Select, { Option } from 'rc-select';

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
		<div className="vite-control vite-select-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<div className="vite-control-body">
				<Select
					value={ choices?.[ value ] ?? '' }
					onChange={ ( val ) => {
						setValue( val );
						setting.set( val );
					} }
				>
					{ Object.entries( choices ).map( ( [ key, val ] ) => (
						<Option key={ key } value={ key }>{ val }</Option>
					) ) }
				</Select>
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

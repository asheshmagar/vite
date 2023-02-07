import { RawHTML, memo, useState } from '@wordpress/element';
import { TextareaControl } from '@wordpress/components';
import { ControlPropsType } from '../types';

const TextArea: React.FC<ControlPropsType> = ( props ) => {
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

	return (
		<div className="vite-control vite-input-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
			<div className="vite-control-body">
				<TextareaControl
					onChange={ ( val: string ) => {
						setValue( val );
						setting.set( val );
					} }
					value={ value }
				/>
			</div>
		</div>
	);
};

export default memo( TextArea );

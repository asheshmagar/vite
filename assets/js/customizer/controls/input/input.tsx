import { RawHTML, memo, useState } from '@wordpress/element';
import { TextControl } from '@wordpress/components';
import { ControlPropsType } from '../types';

const INPUT_TYPES: string[] = [ 'text', 'number', 'email', 'url' ];

const Input: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			setting,
			params,
			params: {
				label,
				description,
				inputAttrs,
			},
		},
	} = props;

	const [ value, setValue ] = useState( setting.get() );

	const type = INPUT_TYPES.some( ( t ) => t === params.type ) ? params.type : ( inputAttrs?.type || 'text' );

	return (
		<div className="vite-control vite-input-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<div className="vite-control-body">
				<TextControl
					autoComplete="off"
					onChange={ val => {
						setValue( val );
						setting.set( val );
					} }
					value={ value }
					type={ type }
				/>
			</div>
			{ description && (
				<RawHTML className="customize-control-description">{ description }</RawHTML>
			) }
		</div>
	);
};
export default memo( Input );

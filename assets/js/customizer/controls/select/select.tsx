import { memo, useState, RawHTML } from '@wordpress/element';
import { SelectControl, Button } from '@wordpress/components';
import { isEqual } from 'lodash';
import { ControlPropsType } from '../types';

type ChoiceType = {
	label: string;
	value: string;
	disabled?: boolean;
}

const Select: React.FC<ControlPropsType> = ( props ) => {
	let {
		control: {
			setting,
			params: {
				label,
				description,
				choices = {} as ChoiceType[],
				default: defaultValue,
				inputAttrs: {
					allow_reset: allowReset = true,
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	if ( ! choices ) return null;

	choices = Object.keys( choices ).map( c => ( {
		value: c,
		label: choices[ c ],
	} ) );

	return (
		<div className="vite-control vite-select-control">
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
};

export default memo( Select );
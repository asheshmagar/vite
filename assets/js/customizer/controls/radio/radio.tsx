import { RawHTML, useState, memo } from '@wordpress/element';
import { RadioControl, Button } from '@wordpress/components';
import { isEqual } from 'lodash';
import { ControlPropsType } from '../types';

const Radio: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				choices = {},
				default: defaultValue,
				inputAttrs: {
					allow_reset: allowReset = true,
				},
			},
		},
	} = props;

	const [ value, setValue ] = useState( setting.get() );

	return (
		<div className="vite-control vite-radio-control">
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
				<RawHTML className="customize-control-description">{ description }</RawHTML>
			) }
		</div>
	);
};

export default memo( Radio );

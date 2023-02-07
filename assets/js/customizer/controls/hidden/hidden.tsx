import { memo } from '@wordpress/element';
import { ControlPropsType } from '../types';

const Hidden: React.FC<ControlPropsType> = ( {
	control: {
		setting,
		params: {
			settings,
		},
	},
} ) => {
	const value = setting.get();
	let name = settings.default;
	name = name.replace( '[', '-' );
	name = name.replace( ']', '' );
	const cssClass = `hidden-field-${ name }`;

	return (
		<input type="hidden" className={ cssClass } value={ JSON.stringify( value ) } />
	);
};
export default memo( Hidden );

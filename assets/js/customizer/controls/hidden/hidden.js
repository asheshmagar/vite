import { memo } from '@wordpress/element';

export default memo( ( props ) => {
	const value = props.control.setting.get();
	let name = props.control.params.settings.default;
	name = name.replace( '[', '-' );
	name = name.replace( ']', '' );
	const cssClass = `hidden-field-${ name }`;

	return (
		<input type="hidden" className={ cssClass } value={ JSON.stringify( value ) } />
	);
} );

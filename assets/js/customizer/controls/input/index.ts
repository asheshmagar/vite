import { registerControl } from '../../utils';
import Input from './input';

export default () => {
	registerControl( 'vite-input', Input );
	registerControl( 'text', Input );
	registerControl( 'email', Input );
	registerControl( 'url', Input );
	registerControl( 'email', Input );
};

import { registerControl } from '../../utils';
import Builder from './builder';
import './customizer.scss';

export default () => {
	registerControl( 'vite-header-builder', ( props ) => <Builder { ...props } context={ 'header' } /> );
	registerControl( 'vite-footer-builder', ( props ) => <Builder { ...props } context={ 'footer' } /> );
};

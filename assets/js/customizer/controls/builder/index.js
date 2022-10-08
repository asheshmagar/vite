import { registerControl } from '../../utils';
import Builder from './builder';
import './customizer.scss';

export default () => {
	registerControl( 'vite-builder', Builder );
};

import { registerControl } from '../../utils';
import HeaderBuilder from './header-builder';
import './customizer.scss';

export default () => {
	registerControl( 'vite-header-builder', HeaderBuilder );
};

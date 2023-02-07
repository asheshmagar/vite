import { registerControl } from '../../utils';
import Color from './color';
import './customizer.scss';

export default () => {
	registerControl( 'vite-color', Color );
};

import { registerControl } from '../../utils';
import Border from './border';
import './customizer.scss';

export default () => {
	registerControl( 'vite-border', Border );
};

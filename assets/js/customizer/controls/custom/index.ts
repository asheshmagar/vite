import { registerControl } from '../../utils';
import Custom from './custom';
import './customizer.scss';

export default () => {
	registerControl( 'vite-custom', Custom );
};

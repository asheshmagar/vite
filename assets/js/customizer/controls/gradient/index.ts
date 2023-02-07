import Gradient from './gradient';
import { registerControl } from '../../utils';
import './customizer.scss';

export default () => {
	registerControl( 'vite-gradient', Gradient );
};

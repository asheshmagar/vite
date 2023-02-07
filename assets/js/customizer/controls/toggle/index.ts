import { registerControl } from '../../utils';
import Toggle from './toggle';
import './customizer.scss';

export default () => {
	registerControl( 'vite-toggle', Toggle );
};

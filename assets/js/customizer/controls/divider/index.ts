import Divider from './divider';
import { registerControl } from '../../utils';
import './customizer.scss';

export default () => {
	registerControl( 'vite-divider', Divider );
};

import Group from './group';
import { registerControl } from '../../utils';
import './customizer.scss';

export default () => {
	registerControl( 'vite-group', Group );
};

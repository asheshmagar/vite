import Sortable from './sortable';
import { registerControl } from '../../utils';
import './customizer.scss';

export default () => {
	registerControl( 'vite-sortable', Sortable );
};

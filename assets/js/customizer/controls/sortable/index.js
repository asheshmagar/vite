import Sortable from './sortable';
import { defineControls } from '../../utils';
import './customizer.scss';

export default () => {
	defineControls( 'vite-sortable', Sortable );
};

import Group from './group';
import { defineControls } from '../../utils';
import './customizer.scss';

export default () => {
	defineControls( 'vite-group', Group );
};

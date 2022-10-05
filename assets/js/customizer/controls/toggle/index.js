import { defineControls } from '../../utils';
import Toggle from './toggle';
import './customizer.scss';

export default () => {
	defineControls( 'vite-toggle', Toggle );
};

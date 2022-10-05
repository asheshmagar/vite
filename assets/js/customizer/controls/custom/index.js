import { defineControls } from '../../utils';
import Custom from './custom';
import './customizer.scss';

export default () => {
	defineControls( 'vite-custom', Custom );
};

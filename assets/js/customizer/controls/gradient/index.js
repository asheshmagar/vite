import Gradient from './gradient';
import { defineControls } from '../../utils';
import './customizer.scss';

export default () => {
	defineControls( 'vite-gradient', Gradient );
};

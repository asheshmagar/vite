import { defineControls } from '../../utils';
import Color from './color';
import './customizer.scss';

export default () => {
	defineControls( 'vite-color', Color );
};

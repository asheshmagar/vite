import Slider from './slider';
import { defineControls } from '../../utils';
import './customizer.scss';

export default () => {
	defineControls( 'vite-slider', Slider );
};

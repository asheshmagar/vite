import Slider from './slider';
import { registerControl } from '../../utils';
import './customizer.scss';

export default () => {
	registerControl( 'vite-slider', Slider );
};

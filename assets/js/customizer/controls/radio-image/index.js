import { defineControls } from '../../utils';
import RadioImage from './radio-image';
import './customizer.scss';

export default () => {
	defineControls( 'vite-radio-image', RadioImage );
};

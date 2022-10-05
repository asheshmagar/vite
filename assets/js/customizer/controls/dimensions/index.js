import { defineControls } from '../../utils';
import Dimensions from './dimensions';
import './customizer.scss';

export default () => {
	defineControls( 'vite-dimensions', Dimensions );
};

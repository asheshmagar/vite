import { registerControl } from '../../utils';
import Dimensions from './dimensions';
import './customizer.scss';

export default () => {
	registerControl( 'vite-dimensions', Dimensions );
};

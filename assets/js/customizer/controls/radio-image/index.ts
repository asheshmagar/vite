import { registerControl } from '../../utils';
import RadioImage from './radio-image';
import './customizer.scss';

export default () => {
	registerControl( 'vite-radio-image', RadioImage );
};

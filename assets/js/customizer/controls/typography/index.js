import { registerControl } from '../../utils';
import Typography from './typography';
import './customizer.scss';

export default () => {
	registerControl( 'vite-typography', Typography );
};

import { registerControl } from '../../utils';
import Background from './background';
import './customizer.scss';

export default () => {
	registerControl( 'vite-background', Background );
};

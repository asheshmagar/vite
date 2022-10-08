import Checkbox from './checkbox';
import { registerControl } from '../../utils';
import './customizer.scss';

export default () => {
	registerControl( 'vite-checkbox', Checkbox );
};

import Radio from './radio';
import { registerControl } from '../../utils';

export default () => {
	registerControl( 'vite-radio', Radio );
};

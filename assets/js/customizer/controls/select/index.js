import { registerControl } from '../../utils';
import Select from './select';

export default () => {
	registerControl( 'vite-select', Select );
};

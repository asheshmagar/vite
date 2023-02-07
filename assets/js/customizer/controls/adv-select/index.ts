import { registerControl } from '../../utils';
import AdvSelect from './adv-select';

export default () => {
	registerControl( 'vite-adv-select', AdvSelect );
};

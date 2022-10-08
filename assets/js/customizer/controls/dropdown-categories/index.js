import { registerControl } from '../../utils';
import DropdownCategories from './dropdown-categories';

export default () => {
	registerControl( 'vite-dropdown-categories', DropdownCategories );
};

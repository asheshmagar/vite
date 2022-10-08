import { registerControl } from '../../utils';
import Navigate from './navigate';

export default () => {
	registerControl( 'vite-navigate', Navigate );
};

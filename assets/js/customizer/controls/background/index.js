import { registerControl } from '../../utils';
import Background from './background';

export default () => {
	registerControl( 'vite-background', Background );
};

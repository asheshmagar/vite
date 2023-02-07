import { registerControl } from '../../utils';
import Textarea from './textarea';

export default () => {
	registerControl( 'vite-textarea', Textarea );
	registerControl( 'textarea', Textarea );
};

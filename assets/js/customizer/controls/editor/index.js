import Editor from './editor';
import { registerControl } from '../../utils';

export default () => {
	registerControl( 'vite-editor', Editor );
};

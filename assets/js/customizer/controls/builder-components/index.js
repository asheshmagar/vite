import BuilderComponents from './builder-components';
import { registerControl } from '../../utils';
import './customizer.scss';

export default () => {
	registerControl( 'vite-builder-components', BuilderComponents );
};

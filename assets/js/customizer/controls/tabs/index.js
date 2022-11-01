import Tabs from './tabs';
import { registerControl } from '../../utils';
import './customizer.scss';

export default () => {
	registerControl( 'vite-tabs', Tabs );
};

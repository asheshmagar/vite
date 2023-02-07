import ButtonSet from './button-set';
import { registerControl } from '../../utils';
import './customizer.scss';

export default () => {
	registerControl( 'vite-buttonset', ButtonSet );
};

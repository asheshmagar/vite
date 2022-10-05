import ButtonSet from './button-set';
import { defineControls } from '../../utils';
import './customizer.scss';

export default () => {
	defineControls( 'vite-buttonset', ButtonSet );
};

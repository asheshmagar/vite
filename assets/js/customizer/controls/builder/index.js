import { defineControls } from '../../utils';
import Builder from './builder';
import './customizer.scss';

export default () => {
	defineControls( 'vite-builder', Builder );
};

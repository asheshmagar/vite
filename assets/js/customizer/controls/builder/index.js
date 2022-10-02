import { defineControls } from '../../utils';
import Builder from './builder';
import './controls.scss';

export default () => {
	defineControls( 'customind-builder', Builder );
};

import { defineControls } from '../../utils';
import Color from './color';
import './controls.scss';

export default () => {
	defineControls( 'customind-color', Color );
};

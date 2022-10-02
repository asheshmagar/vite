import ButtonSet from './button-set';
import { defineControls } from '../../utils';
import './controls.scss';

export default () => {
	defineControls( 'customind-buttonset', ButtonSet );
};

import { defineControls } from '../../utils';
import Toggle from './toggle';
import './controls.scss';

export default () => {
	defineControls( 'customind-toggle', Toggle );
};

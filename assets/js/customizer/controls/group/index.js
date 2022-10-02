import Group from './group';
import { defineControls } from '../../utils';
import './controls.scss';

export default () => {
	defineControls( 'customind-group', Group );
};

import Sortable from './sortable';
import { defineControls } from '../../utils';
import './controls.scss';

export default () => {
	defineControls( 'customind-sortable', Sortable );
};

import { defineControls } from '../../utils';
import Custom from './custom';
import './controls.scss';

export default () => {
	defineControls( 'customind-custom', Custom );
};

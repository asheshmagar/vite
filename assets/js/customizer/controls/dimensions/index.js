import { defineControls } from '../../utils';
import Dimensions from './dimensions';
import './controls.scss';

export default () => {
	defineControls( 'customind-dimensions', Dimensions );
};

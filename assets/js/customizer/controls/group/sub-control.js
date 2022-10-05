import Color from '../color/color';
import Background from '../background/background';
import Typography from '../typography/typography';
import Dimensions from '../dimensions/dimensions';
import ButtonSet from '../button-set/button-set';
import Custom from '../custom/custom';
import Navigate from '../navigate/navigate';
import RadioImage from '../radio-image/radio-image';
import Slider from '../slider/slider';
import Toggle from '../toggle/toggle';
import Gradient from '../gradient/gradient';
import _ from 'lodash';

const SubControl = props => {
	const { name, control: type, customizer, ...otherProps } = props;
	const control = customizer.control( name );
	_.extend( control.params, otherProps );
	switch ( type ) {
		case 'vite-color':
			return <Color control={ control } customizer={ customizer } />;
		case 'vite-background':
			return <Background control={ control } customizer={ customizer } />;
		case 'vite-typography':
			return <Typography control={ control } customizer={ customizer } />;
		case 'vite-dimensions':
			return <Dimensions control={ control } customizer={ customizer } />;
		case 'vite-buttonset':
			return <ButtonSet control={ control } customizer={ customizer } />;
		case 'vite-custom':
			return <Custom control={ control } customizer={ customizer } />;
		case 'vite-navigate':
			return <Navigate control={ control } customizer={ customizer } />;
		case 'vite-radio-image':
			return <RadioImage control={ control } customizer={ customizer } />;
		case 'vite-slider':
			return <Slider control={ control } customizer={ customizer } />;
		case 'vite-toggle':
			return <Toggle control={ control } customizer={ customizer } />;
		case 'vite-gradient':
			return <Gradient control={ control } customizer={ customizer } />;
		default:
			return null;
	}
};

export default SubControl;

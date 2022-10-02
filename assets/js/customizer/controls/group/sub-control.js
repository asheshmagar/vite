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
		case 'customind-color':
			return <Color control={ control } customizer={ customizer } />;
		case 'customind-background':
			return <Background control={ control } customizer={ customizer } />;
		case 'customind-typography':
			return <Typography control={ control } customizer={ customizer } />;
		case 'customind-dimensions':
			return <Dimensions control={ control } customizer={ customizer } />;
		case 'customind-buttonset':
			return <ButtonSet control={ control } customizer={ customizer } />;
		case 'customind-custom':
			return <Custom control={ control } customizer={ customizer } />;
		case 'customind-navigate':
			return <Navigate control={ control } customizer={ customizer } />;
		case 'customind-radio-image':
			return <RadioImage control={ control } customizer={ customizer } />;
		case 'customind-slider':
			return <Slider control={ control } customizer={ customizer } />;
		case 'customind-toggle':
			return <Toggle control={ control } customizer={ customizer } />;
		case 'customind-gradient':
			return <Gradient control={ control } customizer={ customizer } />;
		default:
			return null;
	}
};

export default SubControl;

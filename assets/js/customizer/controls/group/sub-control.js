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
import AdvSelect from '../adv-select/adv-select';
import Select from '../select/select';
import Input from '../input/input';
import Border from '../border/border';
import Checkbox from '../checkbox/checkbox';
import Textarea from '../textarea/textarea';
import _ from 'lodash';

const SubControl = props => {
	const { id, type, customizer, ...otherProps } = props;
	const control = customizer.control( id );

	_.extend( control?.params ?? {}, otherProps );

	const controlProps = {
		control,
		customizer,
	};

	switch ( type ) {
		case 'vite-color':
			return <Color { ...controlProps } />;
		case 'vite-background':
			return <Background { ...controlProps } />;
		case 'vite-typography':
			return <Typography { ...controlProps } />;
		case 'vite-dimensions':
			return <Dimensions { ...controlProps } />;
		case 'vite-buttonset':
			return <ButtonSet { ...controlProps } />;
		case 'vite-custom':
			return <Custom { ...controlProps } />;
		case 'vite-navigate':
			return <Navigate { ...controlProps } />;
		case 'vite-radio-image':
			return <RadioImage { ...controlProps } />;
		case 'vite-slider':
			return <Slider { ...controlProps } />;
		case 'vite-toggle':
			return <Toggle { ...controlProps } />;
		case 'vite-gradient':
			return <Gradient { ...controlProps } />;
		case 'vite-adv-select':
			return <AdvSelect { ...controlProps } />;
		case 'vite-sortable':
			return <AdvSelect { ...controlProps } />;
		case 'vite-select':
		case 'select':
			return <Select { ...controlProps } />;
		case 'input':
		case 'vite-input':
			return <Input { ...controlProps } />;
		case 'vite-border':
			return <Border { ...controlProps } />;
		case 'vite-textarea':
		case 'textarea':
			return <Textarea { ...controlProps } />;
		case 'vite-checkbox':
		case 'checkbox':
			return <Checkbox { ...controlProps } />;
		default:
			return null;
	}
};

export default SubControl;

import { memo } from '@wordpress/element';
import { ControlPropsType } from '../types';

const Divider: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			params: {
				label = '',
			},
		},
	} = props;

	return (
		<div className="vite-control vite-divider-control">
			<div className="vite-control-head">
				<span className="customize-control-title">{ label }</span>
			</div>
		</div>
	);
};
export default memo( Divider );

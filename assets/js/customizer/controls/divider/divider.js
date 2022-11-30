import { memo } from '@wordpress/element';

export default memo( props => {
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
} );

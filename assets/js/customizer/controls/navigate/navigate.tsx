import { memo } from '@wordpress/element';
import { Button, Dashicon } from '@wordpress/components';
import { Tooltip } from '../../components';
import { ControlPropsType } from '../types';

const Navigate: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			params: {
				label,
				description,
				inputAttrs: {
					target_id: targetId,
					target_label: targetLabel,
				},
			},
		},
		customizer,
	} = props;

	if ( ! targetId || ! targetLabel ) {
		return null;
	}

	return (
		<div className="vite-control vite-navigate-control">
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{
						description && (
							<Tooltip>
								<span className="customize-control-description">{ description }</span>
							</Tooltip>
						)
					}
				</div>
			) }
			<div className="vite-control-body">
				<Button onClick={ () => customizer?.section( targetId ) && customizer.section( targetId ).focus() }>
					<span>{ targetLabel }</span>
					<Dashicon icon="arrow-right-alt2" />
				</Button>
			</div>
		</div>
	);
};

export default memo( Navigate );

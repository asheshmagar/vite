import { memo } from '@wordpress/element';
import { Button, Dashicon } from '@wordpress/components';
import { Tooltip } from '../../components';

export default memo( ( props ) => {
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
				<Button onClick={ () => props.customizer?.section( targetId ) && props.customizer.section( targetId ).focus() }>
					<span>{ targetLabel }</span>
					<Dashicon icon="arrow-right-alt2" />
				</Button>
			</div>
		</div>
	);
} );

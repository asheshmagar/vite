import { memo, RawHTML } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { ControlPropsType } from '../types';

const Custom: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			id,
			params: {
				label,
				inputAttrs: {
					info,
					links,
				},
			},
		},
	} = props;

	return (
		<div className="vite-control vite-custom-control" data-control-id={ id }>
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<div className="vite-control-body">
				{ info && (
					<RawHTML>{ info }</RawHTML>
				) }
				{ links && (
					<ul className="vite-links">
						{ links.map( ( l: { url?: string; text: string; }, idx: number ) => (
							<li className="vite-link" key={ idx }>
								<Button variant="primary" href={ l?.url || undefined }>
									{ l.text }
								</Button>
							</li>
						) ) }
					</ul>
				) }
			</div>
		</div>
	);
};

export default memo( Custom );

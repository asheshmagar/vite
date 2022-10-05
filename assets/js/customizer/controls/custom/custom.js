import { memo, RawHTML } from '@wordpress/element';
import { Button } from '@wordpress/components';

export default memo( ( props ) => {
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
						{ links.map( ( l, idx ) => (
							<li className="vite-link" key={ idx }>
								<Button variant="primary" href={ l?.url || null }>
									{ l.text }
								</Button>
							</li>
						) ) }
					</ul>
				) }
			</div>
		</div>
	);
} );

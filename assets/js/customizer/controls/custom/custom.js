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
		<div className="customind-control customind-custom-control" data-control-id={ id }>
			{ label && (
				<div className="customind-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			<div className="customind-control-body">
				{ info && (
					<RawHTML>{ info }</RawHTML>
				) }
				{ links && (
					<ul className="customind-links">
						{ links.map( ( l, idx ) => (
							<li className="customind-link" key={ idx }>
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

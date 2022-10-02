import { memo } from '@wordpress/element';

export default memo( ( props ) => {
	const {
		label,
		description,
		link,
	} = props.control.params;
	return (
		<div className="customind-control customind-title-control">
			{ label && (
				<span className="customize-control-title customind-control__label">{ label }</span>
			) }
			{ description && (
				<span className="customize-control-description customind-control__description">{ description }</span>
			) }
			{ link && (
				<div className="guide-tutorial">
					<span className="control-url">
						<a href={ link } target="_blank" rel="noreferrer">Doc</a>
					</span>
				</div>
			) }
		</div>
	);
} );

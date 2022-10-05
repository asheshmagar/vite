import { memo } from '@wordpress/element';

export default memo( ( props ) => {
	const {
		label,
		description,
		link,
	} = props.control.params;
	return (
		<div className="vite-control vite-title-control">
			{ label && (
				<span className="customize-control-title vite-control__label">{ label }</span>
			) }
			{ description && (
				<span className="customize-control-description vite-control__description">{ description }</span>
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

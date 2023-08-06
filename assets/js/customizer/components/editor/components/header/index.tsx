import { __ } from '@wordpress/i18n';

export default function Header() {
	return (
		<div
			className="vite-editor-header"
			role="region"
			aria-label={ wp.i18n.__( 'Standalone Editor top bar.', 'vite' ) }
			// @ts-ignore
			tabIndex="-1"
		>
			<h1 className="vite-editor-header__title">
				{ wp.i18n.__( 'Editor', 'vite' ) }
			</h1>
		</div>
	);
}

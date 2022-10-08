import { Dashicon, Popover } from '@wordpress/components';
import { noop } from 'lodash';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default ( { onClick = noop } ) => {
	const [ anchor, setAnchor ] = useState( null );
	const [ isOpen, setIsOpen ] = useState( false );

	return (
		<>
			{ /* eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions */ }
			<span
				className="vite-reset"
				onMouseEnter={ () => setIsOpen( true ) }
				onMouseLeave={ () => setIsOpen( false ) }
				ref={ setAnchor }
				onClick={ onClick }
			>
				<Dashicon
					icon="image-rotate"
				/>
			</span>
			{ isOpen && (
				<Popover position="top center" anchor={ anchor } anchorRef={ anchor } className="vite-tooltip">
					{ __( 'Reset' ) }
				</Popover>
			) }
		</>
	);
};

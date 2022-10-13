import { Popover, Button } from '@wordpress/components';
import { useState } from '@wordpress/element';

export default ( { label, ...props } ) => {
	const [ isOpen, setOpen ] = useState( false );
	return (
		<div>
			<Button
				onMouseEnter={ () => setOpen( true ) }
				onMouseLeave={ () => setOpen( false ) }
				{ ...props }
			/>
			{ ( isOpen && label ) && (
				<Popover className="vite-tooltip" position="top center" focusOnMount={ false }>
					{ label }
				</Popover>
			) }
		</div>
	);
};

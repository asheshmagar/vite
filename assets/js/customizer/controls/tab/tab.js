import { memo } from '@wordpress/element';
import { ButtonGroup, Button } from '@wordpress/components';

export default memo( ( props ) => {
	let {
		choices,
		activeTab = '',
	} = props.control.params;

	if ( ! Object.keys( choices )?.length ) {
		return null;
	}

	activeTab = Object.keys( choices )[ 0 ];

	return (
		<div className="vite-control vite-tab-control">
			<div className="vite-control-body">
				<ButtonGroup className="vite-tabs">
					{ Object.entries( choices ).map( ( [ key, value ] ) => (
						<Button
							key={ key }
							className={ `vite-tab vite-${ key }-tab${ activeTab === key ? ' active' : '' }` }
							onClick={ () => {} }
							data-tab={ key }
							data-target={ value?.target || '' }
						>
							<span>{ value?.label || '' }</span>
						</Button>
					) ) }
				</ButtonGroup>
			</div>
		</div>
	);
} );

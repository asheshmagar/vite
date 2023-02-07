import { memo, useEffect, useState } from '@wordpress/element';
import { ButtonGroup, Button } from '@wordpress/components';
import { applyFilters } from '@wordpress/hooks';

const TABS = applyFilters( 'vite.customizer.tabs', [
	{
		name: 'general',
		title: 'General',
	},
	{
		name: 'design',
		title: 'Design',
	},
] );

export default memo( ( props: {
	customizer: any;
} ) => {
	const {
		customizer,
	} = props;
	const [ tab, setTab ] = useState( customizer.state( 'vite-tab' ).get() );
	useEffect( () => {
		customizer.state( 'vite-tab' ).bind( setTab );
	}, [] );

	return (
		<div className="vite-control vite-tabs-control">
			<ButtonGroup className="vite-tabs">
				{ ( TABS as any ).map( ( t: {
					name: string;
					title: string;
				} ) => (
					<Button
						key={ t.name }
						className={ `vite-tab${ t.name === tab ? ' active' : '' }` }
						onClick={ () => customizer.state( 'vite-tab' ).set( t.name ) }
					>
						{ t.title }
					</Button>
				) ) }
			</ButtonGroup>
		</div>
	);
} );

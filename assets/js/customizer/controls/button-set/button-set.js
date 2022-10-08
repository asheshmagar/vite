import { memo, useState, RawHTML } from '@wordpress/element';
import { ButtonGroup, Button } from '@wordpress/components';

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				choices,
				inputAttrs: {
					multiple = false,
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	return (
		<div className="vite-control vite-button-set-control" data-multiple={ multiple }>
			{ label && (
				<div className="vite-control-head">
					<div className="vite-control-title-wrap">
						<span className="customize-control-title">{ label }</span>
					</div>
				</div>
			) }
			<div className="vite-control-body">
				<ButtonGroup>
					{ Object.entries( choices ).map( ( [ key, val ] ) => (
						<Button
							key={ key }
							onClick={ () => {
								if ( multiple ) {
									const temp = [ ...( value || [] ) ];
									if ( temp.includes( key ) ) {
										temp.splice( temp.indexOf( key ), 1 );
									} else {
										temp.push( key );
									}
									setValue( temp );
									setting.set( temp );
								} else {
									setValue( key );
									setting.set( key );
								}
							} }
							variant={ multiple ? ( value?.includes( key ) ? 'primary' : 'secondary' ) : ( value === key ? 'primary' : 'secondary' ) }
							className={ key }
						>
							{ val }
						</Button>
					) ) }
				</ButtonGroup>
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

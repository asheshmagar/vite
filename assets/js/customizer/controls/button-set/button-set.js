import { memo, useState, RawHTML } from '@wordpress/element';
import { ButtonGroup, Button } from '@wordpress/components';
import { isEqual } from 'lodash';

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
				default: defaultValue,
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );

	return (
		<div className="vite-control vite-button-set-control" data-multiple={ multiple }>
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{ ! isEqual( defaultValue, value ) && (
						<Button
							onClick={ () => {
								setValue( defaultValue );
								setting.set( defaultValue );
							} }
							icon="image-rotate"
							className="vite-reset"
						/>
					) }
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

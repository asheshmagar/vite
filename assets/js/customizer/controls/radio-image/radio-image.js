import { memo, useState } from '@wordpress/element';
import { Tooltip } from '../../components';

export default memo( ( props ) => {
	const {
		control: {
			id,
			params: {
				label,
				choices,
				description,
				inputAttrs: {
					image_col: imageCol = 2,
				},
			},
			setting,
		},
	} = props;

	const [ value, setValue ] = useState( setting.get() || '' );

	const update = ( val ) => {
		setValue( val );
		setting.set( val );
	};

	return (
		<div className="vite-control vite-radio-image-control" data-control-id={ id }>
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{ description && (
						<Tooltip>
							<span className="customize-control-description">{ description }</span>
						</Tooltip>
					) }
				</div>
			) }
			<div className="vite-control-body" style={ { '--vite-col': imageCol } }>
				{ Object.entries( choices ).map( ( [ k, v ] ) => (
					<div
						role="button"
						tabIndex={ 0 }
						onClick={ () => update( k ) }
						onKeyDown={ e => {
							if ( e.code === 'Enter' ) {
								update( k );
							}
						} }
						key={ k }
						data-id={ k }
						className={ `vite-radio-image${ value === k ? ' active' : '' }` }
						data-label={ v?.label || null }
					>
						{ v?.url && (
							<img style={ { width: '100%' } } src={ v.url } alt={ v?.label || k } />
						) }
					</div>
				) ) }
			</div>
		</div>
	);
} );

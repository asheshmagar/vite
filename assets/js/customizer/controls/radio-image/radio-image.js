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
		<div className="customind-control customind-radio-image-control" data-control-id={ id }>
			{ label && (
				<div className="customind-control-head">
					<span className="customize-control-title">{ label }</span>
					{ description && (
						<Tooltip>
							<span className="customize-control-description">{ description }</span>
						</Tooltip>
					) }
				</div>
			) }
			<div className="customind-control-body" style={ { '--customind-col': imageCol } }>
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
						className={ `customind-radio-image${ value === k ? ' active' : '' }` }
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

import { memo, useState, useCallback, useEffect, useRef } from '@wordpress/element';
import { cloneDeep, isEqual } from 'lodash';
import BuilderArea from './builder-area';
import { Button } from '@wordpress/components';
import { usePortal } from '../../hooks';

export default memo( ( props ) => {
	const {
		control: {
			id,
			params: {
				inputAttrs: {
					areas = {},
				},
			},
			setting,
			section,
		},
		customizer,
	} = props;

	const [ value, setValue ] = useState( setting.get() || {} );
	const [ open, setOpen ] = useState( false );
	const builderRef = useRef();
	const Portal = usePortal( document.querySelector( '.wp-full-overlay' ) );

	const update = ( row, area, items ) => {
		const newValue = cloneDeep( value );
		newValue[ row ][ area ] = items;
		if ( ! isEqual( newValue, value ) ) {
			setValue( newValue );
			setting.set( newValue );
		}
	};

	const remove = ( row, area, item ) => {
		const newValue = cloneDeep( value );
		newValue[ row ][ area ] = ( newValue?.[ row ]?.[ area ] || [] ).filter( v => v !== item );
		if ( ! isEqual( newValue, value ) ) {
			setValue( newValue );
			setting.set( newValue );
		}
	};

	const getAreaItems = useCallback( ( row, area ) => value?.[ row ]?.[ area ] || [], [ value ] );

	useEffect( () => {
		const sec = customizer.section( section() );
		if ( sec?.panel() ) {
			const panel = customizer.panel( sec.panel() );
			if ( panel ) {
				panel.expanded.bind( isExpanded => {
					setOpen( isExpanded );
				} );
			}
		}
		setOpen( true );
	}, [] );

	useEffect( () => {
		const resizePreviewer = () => {
			if ( ! builderRef.current ) return;
			const height = builderRef.current?.offsetHeight || '';
			if ( open ) {
				customizer.previewer.container.css( 'max-height', `calc(100vh - ${ height }px)` );
				customizer.previewer.container.css( {
					maxHeight: `calc(100vh - ${ height }px)`,
					marginTop: 0,
				} );
			} else {
				props.customizer.previewer.container.css( {
					maxHeight: '100vh',
					marginTop: '0',
				} );
			}
		};
		resizePreviewer();
		window.addEventListener( 'resize', resizePreviewer );
		return () => {
			window.removeEventListener( 'resize', resizePreviewer );
		};
	}, [ open ] );

	return (
		<div className="vite-control vite-builder-control" data-control-id={ id }>
			<Portal>
				<div className={ `vite-builder${ open ? ' open' : '' }` } data-portal-for={ id }>
					<div ref={ builderRef } className="vite-builder-rows-wrap">
						<div className="vite-builder-rows">
							{ Object.entries( areas ).map( ( [ row, columns = {} ] ) => (
								<div key={ row } className={ `vite-builder-row vite-builder-row-${ row }` }>
									<Button className="vite-builder-row-action" icon="admin-generic" />
									<div className="vite-builder-areas">
										{ Object.entries( columns ).map( ( [ area ] ) => {
											return (
												<BuilderArea
													key={ area }
													className={ `vite-builder-area vite-builder-area-${ area }` }
													remove={ ( item ) => remove( row, area, item ) }
													areaItems={ getAreaItems( row, area ) }
													value={ value }
													update={ ( val ) => update( row, area, val ) }
													{ ...props }
												/>
											);
										} ) }
									</div>
								</div>
							) ) }
						</div>
					</div>
				</div>
			</Portal>
		</div>
	);
} );

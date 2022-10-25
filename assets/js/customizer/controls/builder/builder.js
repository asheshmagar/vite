import { memo, useState, useCallback, useEffect, useRef } from '@wordpress/element';
import { cloneDeep, isEqual } from 'lodash';
import BuilderArea from './builder-area';
import { Button } from '@wordpress/components';
import { usePortal } from '../../hooks';
import BuilderAvailableItems from './builder-available-items';

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
	const [ isPanelExpanded, setIsPanelExpanded ] = useState( false );
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
					setIsPanelExpanded( isExpanded );
				} );
			}
		}
		setOpen( true );
	}, [] );

	useEffect( () => {
		const collapseBtn = document.querySelector( '#customize-footer-actions .collapse-sidebar' );

		if ( ! isPanelExpanded || ! collapseBtn ) return;

		const handleClick = e => {
			e.stopPropagation();
			const status = e.currentTarget.getAttribute( 'aria-expanded' );
			setOpen( 'true' === status );
		};
		collapseBtn?.addEventListener( 'click', handleClick );
		return () => {
			collapseBtn?.removeEventListener( 'click', handleClick );
		};
	}, [ isPanelExpanded ] );

	useEffect( () => {
		const resizePreviewer = () => {
			if ( ! builderRef.current ) return;
			const height = builderRef.current?.offsetHeight || '';
			if ( open ) {
				setTimeout( () => {
					customizer.previewer.container.css( 'max-height', `calc(100vh - ${ height }px)` );
					customizer.previewer.container.css( {
						maxHeight: `calc(100vh - ${ height }px)`,
						marginTop: 0,
					} );
				}, 300 );
			} else {
				customizer.previewer.container.css( {
					maxHeight: '',
					marginTop: '',
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
		<>
			<div className="vite-control vite-builder-control">
				<BuilderAvailableItems value={ value } { ...props } />
			</div>
			<Portal>
				<div className="vite-builder" data-portal-for={ id } data-open={ open } >
					<div ref={ builderRef } className="vite-builder-inner">
						<div className="vite-builder-rows">
							{ Object.entries( areas ).map( ( [ row, columns = {} ] ) => (
								<div key={ row } data-row={ row } className="vite-builder-row">
									<Button className="vite-builder-row-action" icon="admin-generic" />
									<div className="vite-builder-cols">
										{ Object.entries( columns ).map( ( [ area ] ) => {
											return (
												<BuilderArea
													key={ area }
													className={ `vite-builder-col` }
													col={ area }
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
		</>
	);
} );

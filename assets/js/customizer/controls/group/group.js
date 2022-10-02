import { memo, useState, useEffect, useRef, useMemo } from '@wordpress/element';
import { Tooltip } from '../../components';
import { Button, TabPanel } from '@wordpress/components';
import SubControl from './sub-control';

export default memo( props => {
	const [ isOpen, setIsOpen ] = useState( false );
	const popover = useRef();
	const trigger = useRef();

	const {
		id,
		control: {
			params: {
				label,
				description,
				inputAttrs: {
					fields = {},
				},
			},
		},
		customizer,
	} = props;

	const tabs = useMemo( () => ( fields?.tabs ? Object.keys( fields.tabs ) : [] ).map( t => ( {
		title: t,
		name: t.toLowerCase(),
	} ) ), [] );

	useEffect( () => {
		const wrapper = document.querySelector( '.wp-full-overlay-sidebar-content' );
		if ( ! popover.current || ! trigger.current || ! wrapper ) return;
		const listener = ( e ) => {
			if ( ! popover.current?.contains( e.target ) && ! trigger.current?.contains( e.target ) ) {
				setIsOpen( false );
			}
		};
		wrapper.addEventListener( 'click', listener );
		return () => {
			wrapper.removeEventListener( 'click', listener );
		};
	}, [] );

	return (
		<>
			<div className="customind-control customind-group-control" data-control-id={ id }>
				{ label && (
					<div className="customind-control-head">
						<span className="customize-control-title">{ label }</span>
						{ description && (
							<Tooltip>
								<span className="customize-control-description">{ description }</span>
							</Tooltip>
						) }
						<Button
							className={ `customind-group-popover-trigger${ isOpen ? ' active' : '' }` }
							ref={ trigger }
							onClick={ () => setIsOpen( open => ! open ) }
							icon={ isOpen ? 'no' : 'edit' }
						/>
					</div>
				) }
			</div>
			<div className="customind-group-popover-wrap">
				<div className={ `customind-group-popover${ isOpen ? ' open' : '' }` } ref={ popover }>
					{ isOpen && (
						<>
							{ tabs?.length > 0 ? (
								<TabPanel
									className="customind-group-tabs"
									activeClass="is-primary"
									tabs={ tabs }
								>
									{ t => (
										<div data-tab={ t.name }>
											{ fields.tabs[ t.title ].map( ( control ) => (
												<SubControl key={ control.name } { ...control } customizer={ customizer } />
											) ) }
										</div>
									) }
								</TabPanel>
							) : (
								fields?.length > 0 && (
									<div className="customind-group-tabs">
										{ fields.map( control => (
											<SubControl key={ control.name } { ...control } customizer={ customizer } />
										) ) }
									</div>
								)
							) }
						</>
					) }
				</div>
			</div>
		</>
	);
} );

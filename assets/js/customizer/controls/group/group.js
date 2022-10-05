import { memo, useState, useRef, useMemo } from '@wordpress/element';
import { Tooltip } from '../../components';
import { Button, TabPanel } from '@wordpress/components';
import SubControl from './sub-control';
import { useClickOutside } from '../../hooks';

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

	useClickOutside( trigger, popover, () => setIsOpen( false ) );

	return (
		<>
			<div className="vite-control vite-group-control" data-control-id={ id }>
				{ label && (
					<div className="vite-control-head">
						<span className="customize-control-title">{ label }</span>
						{ description && (
							<Tooltip>
								<span className="customize-control-description">{ description }</span>
							</Tooltip>
						) }
						<Button
							className={ `vite-group-popover-trigger${ isOpen ? ' active' : '' }` }
							ref={ trigger }
							onClick={ () => setIsOpen( open => ! open ) }
							icon={ isOpen ? 'no' : 'edit' }
						/>
					</div>
				) }
			</div>
			<div className="vite-group-popover-wrap">
				<div className={ `vite-group-popover${ isOpen ? ' open' : '' }` } ref={ popover }>
					{ isOpen && (
						<>
							{ tabs?.length > 0 ? (
								<TabPanel
									className="vite-group-tabs"
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
									<div className="vite-group-tabs">
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

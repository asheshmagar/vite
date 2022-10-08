import { memo, useState, RawHTML } from '@wordpress/element';
import { GradientPicker, Popover } from '@wordpress/components';
import { noop } from 'lodash';

export default memo( ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );
	const [ anchor, setAnchor ] = useState( null );
	const [ isOpen, setIsOpen ] = useState( false );

	return (
		<>
			<div className="vite-control vite-gradient-control">
				{ label && (
					<div className="vite-control-head">
						<span className="customize-control-title">{ label }</span>
					</div>
				) }
				<div className="vite-control-body">
					<div className="vite-gradient-picker">
						<span
							ref={ setAnchor }
							style={ {
								height: 24,
								width: 24,
								borderRadius: '50%',
								boxShadow: 'inset 0 0 0 1px rgb(0 0 0 / 20%)',
								display: 'inline-block',
								background: value,
								cursor: 'pointer',
								justifySelf: 'end',
							} }
							onClick={ () => setIsOpen( prev => ! prev ) }
							role="button"
							onKeyDown={ noop }
							tabIndex={ -1 }
						/>
						{ isOpen && (
							<Popover
								anchor={ anchor }
								anchorRef={ anchor }
								onFocusOutside={ () => setIsOpen( false ) }
								className="vite-popover"
								position="bottom center"
							>
								<GradientPicker
									value={ value } onChange={ val => {
										setValue( val );
										setting.set( val );
									} }
								/>
							</Popover>
						) }

					</div>
				</div>
				{ description && (
					<div className="customize-control-description">
						<RawHTML>{ description }</RawHTML>
					</div>
				) }
			</div>
		</>
	);
} );

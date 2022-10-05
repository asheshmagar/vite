import { memo, useState } from '@wordpress/element';
import { ButtonGroup, Button } from '@wordpress/components';
import { Tooltip } from '../../components';
import { useDeviceSelector } from '../../hooks';

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
					responsive = false,
				},
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );
	const { DeviceSelector } = useDeviceSelector();

	return (
		<div className="vite-control vite-button-set-control" data-multiple={ multiple }>
			{ label && (
				<div className="vite-control-head">
					<div className="vite-control-title-wrap">
						<span className="customize-control-title">{ label }</span>
						{ responsive && <DeviceSelector /> }
					</div>
					{ description && (
						<Tooltip>
							<span className="customize-control-description">{ description }</span>
						</Tooltip>
					) }
				</div>
			) }
			{ responsive ? (
				[ 'desktop', 'tablet', 'mobile' ].map( d => (
					<ButtonGroup key={ d }>
						{ Object.entries( choices ).map( ( [ key, val ] ) => (
							<Button
								key={ key }
								onClick={ () => {
									setValue( key );
									setting.set( key );
								} }
								variant={ key === value ? 'primary' : 'secondary' }
								className={ key }
							>
								{ val }
							</Button>
						) ) }
					</ButtonGroup>
				) )
			) : (
				<ButtonGroup>
					{ Object.entries( choices ).map( ( [ key, val ] ) => (
						<Button
							key={ key }
							onClick={ () => {
								setValue( key );
								setting.set( key );
							} }
							variant={ key === value ? 'primary' : 'secondary' }
							className={ key }
						>
							{ val }
						</Button>
					) ) }
				</ButtonGroup>
			) }
		</div>
	);
} );

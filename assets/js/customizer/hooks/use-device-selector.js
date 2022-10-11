import { useEffect, useMemo, useState } from '@wordpress/element';
import { Button, ButtonGroup, Dropdown } from '@wordpress/components';

export default () => {
	const [ device, setDevice ] = useState( wp?.customize?.previewedDevice() || 'desktop' );

	const listener = () => {
		setDevice( wp?.customize?.previewedDevice() || 'desktop' );
	};

	useEffect( () => {
		if ( ! wp.customize ) return;
		setTimeout( () => wp.customize.previewedDevice.bind( listener ), 1000 );

		return () => {
			if ( ! wp.customize ) return;
			wp.customize.previewedDevice.unbind( listener );
		};
	}, [] );

	const DeviceSelector = useMemo( () => {
		return ( { dropdown = true, ...props } ) => (
			<div className="vite-device-selector" { ...props }>
				{ dropdown ? (
					<Dropdown
						className="vite-devices"
						position="top center"
						renderToggle={ ( { isOpen, onToggle } ) => (
							<Button
								className="vite-device"
								onClick={ onToggle }
								aria-expanded={ isOpen }
								icon={ 'mobile' === device ? 'smartphone' : device }
							/>
						) }
						renderContent={ () => (
							<ButtonGroup>
								{ [ 'desktop', 'tablet', 'mobile' ].map( d => (
									<Button
										key={ d }
										className={ `vite-device ${ d }${ device === d ? ' is-primary' : '' }` }
										onClick={ ( e ) => {
											e.stopPropagation();
											setDevice( d );
											wp?.customize?.previewedDevice?.set( d );
										} }
										icon={ 'mobile' === d ? 'smartphone' : d }
									/>
								) ) }
							</ButtonGroup>
						) }
					/>
				) : (
					<ButtonGroup className="vite-devices">
						{ [ 'desktop', 'tablet', 'mobile' ].map( d => (
							<Button
								key={ d }
								className={ `vite-device ${ d }${ device === d ? ' is-primary' : '' }` }
								onClick={ ( e ) => {
									e.stopPropagation();
									setDevice( d );
									wp?.customize?.previewedDevice?.set( d );
								} }
								icon={ 'mobile' === d ? 'smartphone' : d }
							/>
						) ) }
					</ButtonGroup>
				) }
			</div>
		);
	}, [ device ] );

	return {
		device,
		setDevice( d ) {
			setDevice( d );
			wp?.customize?.previewedDevice?.set( d );
		},
		DeviceSelector,
	};
};

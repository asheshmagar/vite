import { useEffect, useMemo, useState } from '@wordpress/element';
import { Button, ButtonGroup, Dropdown } from '@wordpress/components';
import { Icon } from '../components';

const DeviceButtons: React.FC<{
	device: string;
	setDevice: ( device: string ) => void;
	[ key: string ]: any;
}> = ( { device, setDevice, ...props } ) => (
	<ButtonGroup { ...props }>
		{ [ 'desktop', 'tablet', 'mobile' ].map( d => (
			<Button
				key={ d }
				className={ `vite-device ${ d }${ device === d ? ' is-primary' : '' }` }
				onClick={ ( e: React.SyntheticEvent ) => {
					e.stopPropagation();
					setDevice( d );
					wp?.customize?.previewedDevice?.set( d );
				} }
			>
				<Icon icon={ 'vite-' + d } size={ 16 } />
			</Button>
		) ) }
	</ButtonGroup>
);

const useDeviceSelector = (): {
	device: string;
	setDevice: ( device: string ) => void;
	DeviceSelector: ( props: { dropdown?: boolean, [key: string]: any } ) => JSX.Element;
} => {
	const [ device, setDevice ] = useState<string>( wp?.customize?.previewedDevice() ?? 'desktop' );

	const listener = () => {
		setDevice( wp?.customize?.previewedDevice() ?? 'desktop' );
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
							>
								<Icon icon={ 'vite-' + device } size={ 16 } />
							</Button>
						) }
						renderContent={ () => (
							<DeviceButtons device={ device } setDevice={ setDevice } />
						) }
					/>
				) : (
					<DeviceButtons className="vite-devices" device={ device } setDevice={ setDevice } />
				) }
			</div>
		);
	}, [ device ] );

	return {
		device,
		setDevice( d: string ) {
			setDevice( d );
			wp?.customize?.previewedDevice?.set( d );
		},
		DeviceSelector,
	};
};

export default useDeviceSelector;

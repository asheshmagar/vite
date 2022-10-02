import { useEffect, useMemo, useState } from '@wordpress/element';
import { Button } from '@wordpress/components';

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
		return ( props ) => (
			<div className="customind-device-selector" { ...props }>
				{ [ 'desktop', 'tablet', 'mobile' ].map( d => (
					<Button
						key={ d }
						className={ `${ d }${ device === d ? ' active' : '' }` }
						onClick={ ( e ) => {
							e.stopPropagation();
							setDevice( d );
							wp?.customize?.previewedDevice?.set( d );
						} }
						icon={ 'mobile' === d ? 'smartphone' : d }
					/>
				) ) }
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

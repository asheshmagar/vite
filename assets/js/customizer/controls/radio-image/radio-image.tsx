import { memo, useState, RawHTML, useEffect, useRef } from '@wordpress/element';
import { Popover } from '@wordpress/components';
import { noop } from 'lodash';
import { ControlPropsType } from '../types';

const Item: React.FC<{
	onChange: ( value: any ) => void;
	url: string;
	label: string;
	[key: string]: any;
}> = ( { onChange, url, label, ...props } ) => {
	const [ isOpen, setIsOpen ] = useState( false );
	const anchor: React.RefObject<HTMLDivElement> = useRef( null );

	useEffect( () => {
		if ( ! anchor.current || ! label ) return;

		const listener = () => setIsOpen( prev => ! prev );

		anchor.current.addEventListener( 'mouseenter', listener );
		anchor.current.addEventListener( 'mouseleave', listener );
		return () => {
			anchor.current?.removeEventListener( 'mouseenter', listener );
			anchor.current?.removeEventListener( 'mouseleave', listener );
		};
	}, [ anchor ] );

	return (
		<div className="vite-radio-image-item">
			<div
				role="button"
				tabIndex={ -1 }
				onClick={ onChange }
				onKeyDown={ noop }
				{ ...props }
				ref={ anchor }
			>
				{ url ? (
					<img style={ { width: '100%' } } src={ url } alt={ label } />
				) : (
					<span>{ label }</span>
				) }
			</div>
			{ ( isOpen && url ) && (
				// @ts-ignore
				<Popover className="vite-tooltip" anchor={ anchor as any } anchorRef={ anchor } position="top center">
					{ label }
				</Popover>
			) }
		</div>
	);
};

const RadioImage: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			params: {
				label,
				choices,
				description,
				inputAttrs: {
					col = 2,
				},
			},
			setting,
		},
	} = props;

	const [ value, setValue ] = useState( setting.get() || '' );

	if ( ! choices ) return null;

	const update = ( val: string ) => {
		setValue( val );
		setting.set( val );
	};

	return (
		// @ts-ignore
		<div className="vite-control vite-radio-image-control" style={ { '--vite--col': col } }>
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			{ description && (
				<RawHTML className="customize-control-description">{ description }</RawHTML>
			) }
			<div className="vite-control-body">
				{ Object.entries( choices ).map( ( [ k, v ]: any ) => (
					<Item
						key={ k }
						className={ `vite-radio-image${ value === k ? ' active' : '' }` }
						label={ v?.label ?? k }
						url={ v?.image }
						onChange={ () => update( k ) }
					/>
				) ) }
			</div>
		</div>
	);
};

export default memo( RadioImage );

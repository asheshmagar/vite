import { memo, useState } from '@wordpress/element';
import { ButtonGroup, Button } from '@wordpress/components';
import { isEqual, range } from 'lodash';
import { useDeviceSelector } from '../../hooks';
import { DEVICES } from '../../constants';
import { RawHTML } from '../../components';

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
					allow_reset: allowReset = true,
					responsive = false,
					icon = false,
					cols = 3,
				},
				default: defaultValue,
			},
		},
	} = props;
	const [ value, setValue ] = useState( setting.get() );
	const { DeviceSelector, device } = useDeviceSelector();

	return (
		<div className="vite-control vite-button-set-control" data-multiple={ multiple }>
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
					{ ( ! isEqual( defaultValue, value ) && allowReset ) && (
						<Button
							onClick={ () => {
								setValue( defaultValue );
								setting.set( defaultValue );
							} }
							icon="image-rotate"
							className="vite-reset"
						/>
					) }
					{ responsive && <DeviceSelector /> }
				</div>
			) }
			<div className="vite-control-body">
				{ responsive ? (
					DEVICES.map( ( d ) => (
						device === d && (
							<ButtonGroup key={ d } className={ `cols-${ cols }` }>
								{ Object.keys( choices ).map( ( choice, i, arr ) => (
									<Button
										key={ choice }
										isPrimary={ value?.[ d ] === choice }
										isSecondary={ value?.[ d ] !== choice }
										onClick={ () => {
											const temp = { ...value };
											temp[ d ] = choice;
											setValue( temp );
											setting.set( temp );
										} }
										style={ {
											marginTop: cols > i ? undefined : '-1px',
											marginLeft: arr.length > cols ? ( range( cols, arr.length, cols ).some( r => r === i ) ? '0px' : undefined ) : undefined,
										} }
									>
										{ icon ? (
											<RawHTML tag="span" className="vite-icon-wrap">
												{ choices[ choice ] }
											</RawHTML>
										) : (
											choices[ choice ]
										) }
									</Button>
								) ) }
							</ButtonGroup>
						)
					) )
				) : (
					<ButtonGroup className={ `cols-${ cols }` }>
						{ Object.entries( choices ).map( ( [ key, val ], i, arr ) => (
							<Button
								style={ {
									marginTop: cols > i ? undefined : '-1px',
									marginLeft: arr.length > cols ? ( range( cols, arr.length, cols ).some( r => r === i ) ? '0px' : undefined ) : undefined,
								} }
								key={ key }
								onClick={ () => {
									if ( multiple ) {
										const temp = [ ...( value || [] ) ];
										if ( temp.includes( key ) ) {
											temp.splice( temp.indexOf( key ), 1 );
										} else {
											temp.push( key );
										}
										setValue( temp );
										setting.set( temp );
									} else {
										setValue( key );
										setting.set( key );
									}
								} }
								variant={ multiple ? ( value?.includes( key ) ? 'primary' : 'secondary' ) : ( value === key ? 'primary' : 'secondary' ) }
								className={ key }
							>
								{ icon ? (
									<RawHTML tag="span" className="vite-icon-wrap">
										{ val }
									</RawHTML>
								) : (
									val
								) }
							</Button>
						) ) }
					</ButtonGroup>
				) }
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
} );

import { memo, useState, RawHTML, useEffect, useMemo } from '@wordpress/element';
import { SelectControl, Button } from '@wordpress/components';
import { isEqual } from 'lodash';
import { useDeviceSelector } from '../../hooks';
import { ControlPropsType } from '../types';

const AdvSelect: React.FC<ControlPropsType> = ( props ) => {
	const {
		control: {
			setting,
			params: {
				label,
				description,
				choices = {},
				default: defaultValue,
				inputAttrs: {
					allow_reset: allowReset = true,
					choice_dep: choiceDep = null,
					responsive = false,
				},
			},
		},
		customizer,
	} = props;
	const [ value, setValue ] = useState( setting.get() );
	const [ dependency, setDependency ] = useState( choiceDep ? customizer( choiceDep ).get() : null );
	const { device, DeviceSelector } = useDeviceSelector();

	useEffect( () => {
		if ( ! dependency ) return;
		customizer( choiceDep ).bind( ( val: any ) => {
			setDependency( val );
		} );
	}, [] );

	const options = useMemo( () => {
		if ( ! Object.keys( choices )?.length ) return [];
		let results;

		if ( ! responsive ) {
			if ( choiceDep ) {
				results = choices?.[ parseInt( dependency ) - 1 ] || [];
			} else {
				results = choices;
			}
		} else {
			if ( choiceDep ) { // eslint-disable-line no-lonely-if
				results = choices?.[ parseInt( dependency ) - 1 ]?.[ device ] || [];
			} else {
				results = choices?.[ device ] || [];
			}
		}

		return Object.entries( results ?? {} )?.map( ( [ key, val ] ) => ( {
			value: key,
			label: val,
		} ) );
	}, [ dependency, device ] );

	const currentValue = useMemo( () => {
		if ( ! responsive && ! choiceDep ) {
			return value;
		}

		if ( ! responsive && choiceDep ) {
			if ( value?.[ parseInt( dependency ) ] ) return value[ parseInt( dependency ) ];
			if ( value?.[ 0 ] ) return value[ 0 ];
		}

		if ( responsive && ! choiceDep ) {
			if ( value?.[ device ] ) return value[ device ];
			if ( value?.desktop ) return value.desktop;
		}

		if ( responsive && choiceDep ) {
			if ( value?.[ parseInt( dependency ) ]?.[ device ] ) return value[ parseInt( dependency ) ][ device ];
			if ( value?.[ parseInt( dependency ) ]?.desktop ) return value[ parseInt( dependency ) ].desktop;
			if ( value?.[ 0 ]?.[ device ] ) return value[ 0 ][ device ];
			if ( value?.[ 0 ]?.desktop ) return value[ 0 ].desktop;
		}

		return '';
	}, [ value, device, dependency ] );

	const onChange = ( val: any ) => {
		if ( responsive && ! choiceDep ) {
			setValue( {
				...value,
				[ device ]: val,
			} );
			setting.set( {
				...value,
				[ device ]: val,
			} );
			return;
		}
		if ( ! responsive && ! choiceDep ) {
			setValue( val );
			setting.set( val );
			return;
		}
		if ( responsive && choiceDep ) {
			setValue( {
				...value,
				[ dependency ]: {
					...value[ dependency ],
					[ device ]: val,
				},
			} );
			setting.set( {
				...value,
				[ dependency ]: {
					...value[ dependency ],
					[ device ]: val,
				},
			} );
			return;
		}
		if ( ! responsive && choiceDep ) {
			setValue( {
				...value,
				[ dependency ]: val,
			} );
			setting.set( {
				...value,
				[ dependency ]: val,
			} );
		}
	};

	if ( ! options?.length ) {
		return null;
	}

	return (
		<div className="vite-control vite-adv-select-control">
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
				<SelectControl
					value={ currentValue }
					onChange={ onChange }
					options={ options }
					className="vite-adv-select"
				/>
			</div>
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
		</div>
	);
};

export default memo( AdvSelect );

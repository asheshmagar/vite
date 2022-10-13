import { memo, useState, RawHTML } from '@wordpress/element';
import { ViteColorPicker } from '../../components';

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
						<ViteColorPicker
							value={ value }
							onChange={ val => {
								setting.set( val );
								setValue( val );
							} }
							type="gradient"
						/>
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

import { useEffect, useState, memo } from '@wordpress/element';
import { Tooltip } from '../../components';
import { debounce } from 'lodash';

export default memo( ( props ) => {
	const [ value, setValue ] = useState( '' );

	const {
		control: {
			id,
			params: {
				label,
				description,
			},
			setting,
		},
	} = props;

	useEffect( () => {
		setValue( props.control.setting.get() );

		const initialize = () => {
			wp.editor.initialize( ( id ?? 'customind_editor' ), {
				quicktags: true,
				mediaButtons: true,
				tinymce: {
					wpautop: true,
					toolbar1: 'bold italic bullist numlist link',
					toolbar2: '',
					height: 150,
					setup: editor => {
						editor.on( 'Paste Change input Undo Redo', debounce( () => {
							const content = editor.getContent();
							update( content );
						}, 250 ) );
					},
				},
			} );
		};

		if ( document.readyState === 'complete' ) {
			initialize();
		} else {
			document.addEventListener( 'DOMContentLoaded', initialize );
		}
		return () => {
			document.removeEventListener(
				'DOMContentLoaded',
				initialize
			);
			wp.editor.remove( ( id ?? 'customind_editor' ) );
		};
	}, [] );

	const update = ( val ) => {
		setValue( val );
		setting.set( val );
	};

	return (
		<div className="customind-control customind-editor-control" data-control-id={ id }>
			{ label && (
				<div className="customind-control-head">
					<span className="customize-control-title">{ label }</span>
					{ description && (
						<Tooltip>
							<span className="customize-control-description">{ description }</span>
						</Tooltip>
					) }
				</div>
			) }
			<textarea
				className="wp-editor-area"
				id={ id || 'customind_editor' }
				value={ value }
				onChange={ e => {
					update( e.target.value );
				} }
			/>
		</div>
	);
} );

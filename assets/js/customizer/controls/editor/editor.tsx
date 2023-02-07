import { useEffect, useState, memo, RawHTML } from '@wordpress/element';
import { debounce } from 'lodash';
import { ControlPropsType } from '../types';

const Editor: React.FC<ControlPropsType> = ( props ) => {
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

	const editorId = `vite-editor-${ id.replace( /\[/g, '_' ).replace( /]/g, '_' ) }`;

	const [ value, setValue ] = useState( setting.get() ?? '' );
	const editor = wp.oldEditor ?? wp.editor;

	useEffect( () => {
		editor.initialize( editorId, {
			quicktags: true,
			mediaButtons: true,
			tinymce: {
				toolbar1:
					'formatselect,styleselect,bold,italic,bullist,numlist,link,alignleft,aligncenter,alignright,wp_adv',
				toolbar2:
					'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
				setup: ( e: any ) => {
					e.on( 'Paste Change input Undo Redo', debounce( () => {
						const content = e.getContent();
						update( content );
					}, 250 ) );
				},
				style_formats_merge: true,
				style_formats: [],
			},
		} );

		return () => {
			if ( ! window.tinymce.editors[ editorId ] ) return;
			editor.remove( editorId );
		};
	}, [] );

	const update = ( val: string ) => {
		setValue( val );
		setting.set( val );
	};

	return (
		<div className="vite-control vite-editor-control" data-control-id={ id }>
			{ label && (
				<div className="vite-control-head">
					<span className="customize-control-title">{ label }</span>
				</div>
			) }
			{ description && (
				<div className="customize-control-description">
					<RawHTML>{ description }</RawHTML>
				</div>
			) }
			<div className="vite-control-body">
				<textarea
					className="wp-editor-area"
					id={ editorId }
					value={ value }
					onChange={ e => {
						update( e.target.value );
					} }
				/>
			</div>
		</div>
	);
};

export default memo( Editor );

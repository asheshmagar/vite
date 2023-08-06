import '@wordpress/format-library';
import { useSelect, useDispatch } from '@wordpress/data';
import { useEffect, useState, useMemo } from '@wordpress/element';
import { serialize, parse } from '@wordpress/blocks';
import { MediaItem, uploadMedia, UploadMediaErrorCode } from '@wordpress/media-utils';
import { createHigherOrderComponent } from '@wordpress/compose';

import {
	// @ts-ignore
	BlockEditorKeyboardShortcuts,
	BlockEditorProvider,
	BlockList,
	// @ts-ignore
	BlockTools,
	BlockInspector,
	WritingFlow,
	ObserveTyping,
} from '@wordpress/block-editor';
import { removeFilter, addFilter } from '@wordpress/hooks';

import Sidebar from '../sidebar';
const test = createHigherOrderComponent( BlockEdit => {
	removeFilter( 'editor.BlockEdit', 'core/customize-widgets/block-edit' );
	return props => {
		console.log( props );
		return <BlockEdit { ...props } />;
	};
}, 'test' );

addFilter( 'editor.BlockEdit', 'vite/customizer-editor', test, 0 );

type BlockEditorPropsType = {
	settings: {
		[key: string]: any;
	}
}

const BlockEditor: React.FC<BlockEditorPropsType> = ( props ) => {
	const { settings: _settings } = props;
	const [ blocks, updateBlocks ] = useState( [] );
	const { createInfoNotice } = useDispatch( 'core/notices' );

	const canUserCreateMedia = useSelect( ( select ) => {
		const _canUserCreateMedia = ( select( 'core' ) as any )?.canUser( 'create', 'media' );
		return _canUserCreateMedia || _canUserCreateMedia !== false;
	}, [] );

	const settings = useMemo( () => {
		if ( ! canUserCreateMedia ) {
			return _settings;
		}
		return {
			..._settings,
			mediaUpload( { onError, ...rest }: {
				onError: ( message: string ) => void;
				[key: string]: any;
			} ): void {
				uploadMedia( {
					// @ts-ignore
					filesList: undefined,
					maxUploadFileSize: 0,
					onFileChange( files: MediaItem[] ): void { // eslint-disable-line
					},
					wpAllowedMimeTypes: _settings.allowedMimeTypes,
					onError: ( { message }: { message: string } ) => onError( message ),
					...rest,
				} );
			},
		};
	}, [ canUserCreateMedia, _settings ] );

	const handleUpdateBlocks: ( args: any ) => void = ( _blocks ) => {
		updateBlocks( _blocks );
	};

	const handlePersistBlocks: ( args: any ) => void = ( newBlocks ) => {
		updateBlocks( newBlocks );
		// window.localStorage.setItem( 'getdavesbeBlocks', serialize( newBlocks ) );
	};
	return (
		<div className="vite-block-editor">
			<BlockEditorProvider
				value={ blocks }
				onInput={ handleUpdateBlocks }
				onChange={ handlePersistBlocks }
				settings={ settings }
			>
				<Sidebar.InspectorFill>
					<BlockInspector />
				</Sidebar.InspectorFill>
				<div className="editor-styles-wrapper">
					{ /*@ts-ignore*/ }
					<BlockEditorKeyboardShortcuts.Register />
					<BlockTools>
						<WritingFlow>
							<ObserveTyping>
								<BlockList className="vite-block-editor__block-list" />
							</ObserveTyping>
						</WritingFlow>
					</BlockTools>
				</div>
			</BlockEditorProvider>
		</div>
	);
};

export default BlockEditor;

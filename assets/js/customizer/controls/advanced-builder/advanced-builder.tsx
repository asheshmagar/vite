import { ControlPropsType } from '../types';
import { Modal, Button } from '@wordpress/components';
import { useState } from '@wordpress/element';
import IsolatedBlockEditor, { EditorLoaded } from '@automattic/isolated-block-editor';
import { noop } from 'lodash';
import Dialog from 'rc-dialog';

const AdvancedBuilder: React.FC<ControlPropsType> = ( props ) => {
	const [ isOpen, setIsOpen ] = useState( false );

	const handleSave: ( args: string ) => void = ( content ) => {
		// eslint-disable-next-line
		console.log( content ); console.log(props);
	};

	const close = () => {
		setIsOpen( false );
		parent.window.dispatchEvent( new CustomEvent( 'vite-editor-unloaded' ) );
	};

	return (
		<>
			<Button variant="secondary" onClick={ () => setIsOpen( true ) }>
				Open Builder
			</Button>
			<Dialog
				visible={ isOpen }
				destroyOnClose={ true }
				onClose={ close }
				className="vite-advanced-builder"
				footer={ [
					<Button variant="secondary" key="close" onClick={ close }>Close</Button>,
				] }
			>
				<IsolatedBlockEditor
					settings={ {
						editor: {
							availableTemplates: [],
							disablePostFormats: true,
							__experimentalBlockPatterns: [],
							__experimentalBlockPatternCategories: [],
							supportsTemplateMode: false,
							enableCustomFields: false,
							generateAnchors: false,
							canLockBlocks: false,
							postLock: false,
							styles: _VITE_CUSTOMIZER_.editorStyles,
						},
						iso: {
							blocks: {
								allowBlocks: [
									'core/group',
									'core/columns',
									'core/column',
									'vite/block',
									'core/separator',
									'core/spacer',
								],
							},
							moreMenu: {
								topToolbar: true,
							},
							sidebar: {
								inserter: true,
								inspector: true,
							},
							toolbar: {
								navigation: true,
								inspector: true,
							},
							allowEmbeds: [],
						},
						editorType: 'core',
						allowUrlEmbeds: false,
					} }
					onSave={ handleSave }
					onLoad={ () => {
						parent.window.dispatchEvent( new CustomEvent( 'vite-editor-loaded' ) );
					} }
					onError={ () => document.location.reload() }
				>
					<EditorLoaded onLoaded={ noop } />
				</IsolatedBlockEditor>
			</Dialog>
		</>
	);
};

export default AdvancedBuilder;

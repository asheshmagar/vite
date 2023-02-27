import { registerControl } from '../../utils';
import AdvancedBuilder from './advanced-builder';
import './customizer.scss';
import { removeFilter } from '@wordpress/hooks';
import registerBlock from './blocks';
import { getBlockType, unregisterBlockType } from '@wordpress/blocks';

registerBlock();

window.addEventListener( 'vite-editor-loaded', () => {
	if ( ! getBlockType( 'vite/block' ) ) {
		registerBlock();
	}
	removeFilter( 'editor.BlockEdit', 'core/customize-widgets/block-edit' );
} );

window.addEventListener( 'vite-editor-unloaded', () => {
	if ( getBlockType( 'vite/block' ) ) {
		unregisterBlockType( 'vite/block' );
	}
} );

export default () => {
	registerControl( 'vite-advanced-builder', AdvancedBuilder );
};

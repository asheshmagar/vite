import { registerBlockType } from '@wordpress/blocks';

export default () => {
	registerBlockType( 'vite/block', {
		title: 'Vite Block',
		category: 'common',
		icon: 'smiley',
		edit: () => {
			return <p>Vite Block</p>;
		},
		save: () => {
			return <p>Vite Block</p>;
		},
		attributes: {},
	} );
};

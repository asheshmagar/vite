import { createReduxStore, register } from '@wordpress/data';

export const VITE_EDITOR_STORE = 'vite-editor';

const DEFAULT_STATE = {
	editorLoaded: false,
};

const actions = {
	setEditorLoaded: ( loaded: boolean ) => {
		return {
			type: 'SET_EDITOR_LOADED',
			editorLoaded: loaded,
		};
	},
};

const store = createReduxStore( VITE_EDITOR_STORE, {
	reducer( state = DEFAULT_STATE, action: any ) {
		if ( 'SET_EDITOR_LOADED' === action.type ) {
			return {
				...state,
				editorLoaded: action.editorLoaded,
			};
		}
		return state;
	},
	actions,
	selectors: {
		isEditorLoaded: ( state: any ) => state.editorLoaded,
	},
} );

register( store );

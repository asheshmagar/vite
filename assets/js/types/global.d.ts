declare global {
	interface Document {
		querySelectorAll: (selectors: string) => NodeListOf<Element>;
	}
}

declare var wp: any;
declare var tinymce: any;
declare var _VITE_: {
	initMasonryInfiniteScroll: () => void;
	initNavigation: () => void;
	initModals: () => void;
	initScrollToTop: () => void;
	init: () => void;
	publicPath: string;
};
declare var _VITE_CUSTOMIZER_PREVIEW_: {
	configs: {
		[key: string]: any
	}[];
};
declare var _VITE_CUSTOMIZER_: {
	icons: {
		[key: string]: string
	}[];
	condition: {
		[key:string]: {
			[key:string]: any
		}
	};
	conditions: {
		[key: string]: {
			relation?: 'AND' | 'OR';
			terms?: {
				name: string;
				operator: '===' | '!==' | '==' | '!=' | '>' | '>=' | '<' | '<=' | 'contains' | '!contains' | 'in' | '!in';
				value: any;
			};
		}
	};
	publicPath: string;
	resetNonce: string;
	ajaxURL: string;
};

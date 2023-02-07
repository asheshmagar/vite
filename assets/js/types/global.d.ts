declare global {
	interface Document {
		querySelectorAll: (selectors: string) => NodeListOf<Element>;
	}
}

declare var wp: any;
declare var tinymce: any;
declare var _VITE_: any;
declare var _VITE_CUSTOMIZER_PREVIEW_: any;
declare var _VITE_CUSTOMIZER_: any;

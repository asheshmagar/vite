import $ from 'jquery';
import './customizer-preview.scss';
import PreviewStyles from './preview-styles';

const api = wp.customize;

$( document ).ready( () => {
	api.selectiveRefresh.bind( 'partial-content-rendered', ( placement ) => {
		if ( ! window?._VITE_ ) return;
		if ( -1 !== placement?.partial?.id?.indexOf( 'archive' ) ) {
			_VITE_.initMasonryInfiniteScroll();
		}
		if ( -1 !== placement?.partial?.id?.indexOf( 'header' ) ) {
			_VITE_.initNavigation();
			_VITE_.initModals();
		}
		if ( -1 !== placement?.partial?.id?.indexOf( 'scroll-to-top' ) ) {
			_VITE_.initScrollToTop();
		}
	} );

	new PreviewStyles( api, window?._VITE_CUSTOMIZER_PREVIEW_?.configs ?? {} );
} );

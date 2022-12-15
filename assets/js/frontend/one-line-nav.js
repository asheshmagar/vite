import { $ } from './utils';

class OneLineNav {
	menuUL = null;
	items = [];
	itemWidths = [];
	itemVisible = [];
	extraWidth = 0;
	windowWidth = 0;
	extraItem = null;
	timeout = 0;

	constructor( {
		menu,
		onInit = () => {},
		onResize = () => {},
		onUpdate = () => {},
	} ) {
		this.windowWidth = window.innerWidth;

		if ( 'string' === typeof menu ) {
			menu = $( menu );
		}
		if ( ! ( menu instanceof HTMLUListElement ) ) {
			return;
		}
		this.menuUL = menu;
		this.items = this.menuUL.querySelectorAll( ':scope > li' );

		if ( this.items?.length < 1 ) return;

		this.onInit = onInit;
		this.onResize = onResize;
		this.onUpdate = onUpdate;

		this.#init();
	}

	#init() {
		for ( const item of this.items ) {
			const width = item.offsetWidth;
			this.itemWidths.push( width );
			this.itemVisible.push( true );
		}
		this.#prepareHTML();
		this.menuUL.appendChild( this.extraItem );
		this.extraWidth = this.extraItem.offsetWidth;
		this.#listeners();
		this.onInit.call( this );
	}

	#prepareHTML() {
		const temp = document.implementation.createHTMLDocument( '' );
		temp.body.innerHTML = `
			<li class="vite-nav__item vite-nav__item--more vite-nav__item--parent" style="display: none">
				<a href="#">More <span class="vite-nav__submenu-icon" role="presentation"><svg class="chevron-down vite-icon" height="10" width="10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"></path></svg></span></a>
				<button aria-expanded="false" aria-label="Open sub menu" class="vite-nav__submenu-toggle vite-nav__submenu-toggle--hidden"></button>
				<ul class="vite-nav__submenu vite-nav__submenu--more"></ul>
			</li>
		`;
		this.extraItem = temp.body.children[ 0 ];
		this.extraItem.querySelector( 'a' ).addEventListener( 'click', ( e ) => {
			e.preventDefault();
			this.extraItem.classList.toggle( 'vite-nav__item--active' );
		} );
	}

	#onResize() {
		if ( this.windowWidth !== window.innerWidth ) {
			const count = 0;
			for ( const item of this.items ) {
				const width = item.offsetWidth;
				if ( width > 0 ) {
					this.itemWidths[ count ] = width;
				}
			}

			this.extraWidth = this.extraItem.offsetWidth;
			this.#update();
			this.windowWidth = window.innerWidth;
			this.onResize.call( this );
		}
	}

	#listeners() {
		window.addEventListener( 'resize', () => {
			clearTimeout( this.timeout );
			this.timeout = setTimeout( () => {
				this.#onResize();
			}, 500 );
		} );
	}

	#update() {
		let room = true;
		let count = 0;
		let tempWidth = 0;
		let totalWidth = 0;
		const containerWidth = OneLineNav.#innerWidth( this.menuUL.closest( '.vite-nav' ) );
		const noItems = this.items.length - 1;

		for ( const item of this.items ) {
			tempWidth = totalWidth + this.itemWidths[ count ];

			if ( ( ( tempWidth < ( containerWidth - this.extraWidth ) ) || ( ( tempWidth < ( containerWidth ) ) && ( count === noItems ) ) ) && ( room === true ) ) {
				totalWidth = tempWidth;

				if ( ! this.itemVisible[ count ] ) {
					this.extraItem.before( this.extraItem.querySelector( '.vite-nav__submenu--more' ).querySelector( 'li' ) );
					this.itemVisible[ count ] = true;

					if ( count === noItems ) {
						$( '.vite-nav__item--more' ).style.display = 'none';
					}
				}
			} else {
				if ( room ) {
					room = false;

					if ( 0 === count ) {
						this.menuUL.parentNode.classList.add( 'vite-nav--all-hidden' );
					} else {
						this.menuUL.parentNode.classList.remove( 'vite-nav--all-hidden' );
					}

					this.extraItem.style.display = '';
				}
				this.extraItem.querySelector( '.vite-nav__submenu--more' ).appendChild( item );
				this.itemVisible[ count ] = false;
			}
			count++;
		}
		this.onUpdate.call( this );
	}

	static #innerWidth( el ) {
		const style = window.getComputedStyle( el );
		return el.offsetWidth - parseFloat( style.paddingLeft ) - parseFloat( style.paddingRight );
	}
}

export default OneLineNav;

// @ts-nocheck
import { serialize, parse, createBlock } from '@wordpress/blocks';
import { addWidgetIdToBlock } from '@wordpress/widgets';

export function settingIdToWidgetId( settingId ) {
	const matches = settingId.match( /^widget_(.+)(?:\[(\d+)\])$/ );

	if ( matches ) {
		const idBase = matches[ 1 ];
		const number = parseInt( matches[ 2 ], 10 );

		return `${ idBase }-${ number }`;
	}

	return settingId;
}

export function blockToWidget( block, existingWidget = null ) {
	let widget;

	const isValidLegacyWidgetBlock =
		block.name === 'core/legacy-widget' &&
		( block.attributes.id || block.attributes.instance );

	if ( isValidLegacyWidgetBlock ) {
		if ( block.attributes.id ) {
			widget = {
				id: block.attributes.id,
			};
		} else {
			const { encoded, hash, raw, ...rest } = block.attributes.instance;

			widget = {
				idBase: block.attributes.idBase,
				instance: {
					...existingWidget?.instance,
					is_widget_customizer_js_value: true,
					encoded_serialized_instance: encoded,
					instance_hash_key: hash,
					raw_instance: raw,
					...rest,
				},
			};
		}
	} else {
		const instance = {
			content: serialize( block ),
		};
		widget = {
			idBase: 'block',
			widgetClass: 'WP_Widget_Block',
			instance: {
				raw_instance: instance,
			},
		};
	}

	const { form, rendered, ...restExistingWidget } = existingWidget || {};

	return {
		...restExistingWidget,
		...widget,
	};
}

export function widgetToBlock( { id, idBase, number, instance } ) {
	let block;

	const {
		encoded_serialized_instance: encoded,
		instance_hash_key: hash,
		raw_instance: raw,
		...rest
	} = instance;

	if ( idBase === 'block' ) {
		const parsedBlocks = parse( raw.content ?? '', {
			__unstableSkipAutop: true,
		} );
		block = parsedBlocks.length
			? parsedBlocks[ 0 ]
			: createBlock( 'core/paragraph', {} );
	} else if ( number ) {
		block = createBlock( 'core/legacy-widget', {
			idBase,
			instance: {
				encoded,
				hash,
				raw,
				...rest,
			},
		} );
	} else {
		block = createBlock( 'core/legacy-widget', {
			id,
		} );
	}

	return addWidgetIdToBlock( block, id );
}

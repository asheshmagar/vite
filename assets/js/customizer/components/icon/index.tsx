import { memo } from '@wordpress/element';
import { sprintf } from '@wordpress/i18n';
import meta from '../../../../json/font-awesome.json';
import RawHTML from '../raw-html';

type PropsType = {
	icon: string;
	size?: number;
	className?: string;
}

const Icon: React.FC<PropsType> = ( props ) => {
	const {
		icon,
		size = 24,
		className = 'vite-icon',
	} = props;

	if ( ! icon || ! meta?.[ icon ] ) return null;

	const svg = sprintf( meta[ icon ], className, size, size );
	return (
		<RawHTML tag="span" className="vite-icon-wrap">
			{ svg }
		</RawHTML>
	);
};

export default memo( Icon );

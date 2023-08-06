/**
 * WordPress dependencies
 */
import { useSelect, useDispatch } from '@wordpress/data';
import { SnackbarList } from '@wordpress/components';

const Notices = () => {
	const notices = useSelect(
		( select ) =>
			( select( 'core/notices' ) as any )
				.getNotices()
				.filter( ( notice: { type: string|undefined; [key:string]: any } ) => notice.type === 'snackbar' ),
		[]
	);
	const { removeNotice } = useDispatch( 'core/notices' );
	return (
		<SnackbarList
			className="edit-site-notices"
			notices={ notices }
			onRemove={ removeNotice }
		/>
	);
};

export default Notices;

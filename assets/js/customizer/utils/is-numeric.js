export default ( str ) => {
	if ( typeof str !== 'string' ) return false;
	return ! isNaN( str ) && ! isNaN( parseFloat( str ) );
};
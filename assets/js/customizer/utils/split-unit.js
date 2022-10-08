export default ( string ) => {
	if ( typeof string === 'string' && string !== '' ) {
		if ( /\d/.test( string ) ) {
			const split = string.match( /^([-.\d]+(?:\.\d+)?)(.*)$/ );
			return [ split[ 1 ].trim(), split[ 2 ].trim() ];
		}
		return [ '', string ];
	}

	return [ string, '' ];
};

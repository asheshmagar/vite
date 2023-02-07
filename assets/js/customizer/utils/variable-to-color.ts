export default ( variable: string, palette: any = [] ) => {
	if ( variable?.startsWith( 'var(' ) ) {
		const regex = /var\(([^)]+)\)/;
		const match = variable.match( regex );
		if ( match ) {
			const variableName = match[ 1 ];
			return palette.find( ( p: any ) => p.name === variableName )?.value;
		}
	}
	return variable;
};

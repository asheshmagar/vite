const getObjectValues = ( object ) => object && 'object' === typeof object ? Object.values( object ).map( getObjectValues ).flat() : [ object ];

export default getObjectValues;

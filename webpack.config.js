const EslintPlugin = require( 'eslint-webpack-plugin' );
const WebpackBar = require( 'webpackbar' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const { resolve } = require( 'path' );
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );
const CompressionPlugin = require( 'compression-webpack-plugin' );
const { WebpackManifestPlugin } = require( 'webpack-manifest-plugin' );

// eslint-disable-next-line no-unused-vars
module.exports = ( _, args ) => ( {
	entry: {
		customizer: resolve( process.cwd(), 'assets/js/customizer', 'index.ts' ),
		'customizer-preview': resolve( process.cwd(), 'assets/js/customizer/preview', 'index.ts' ),
		frontend: resolve( process.cwd(), 'assets/js/frontend', 'index.ts' ),
		// style: resolve( process.cwd(), 'assets/scss', 'style.scss' ),
		'editor-style': resolve( process.cwd(), 'assets/scss', 'editor-style.scss' ),
	},
	output: {
		filename: '[name].[contenthash:8].js',
		path: resolve( process.cwd(), 'assets/dist' ),
		clean: true,
	},
	resolve: {
		extensions: [ '.js', '.jsx', '.ts', '.tsx' ],
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx|svg|ts|tsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
				},
			},
			{
				test: /\.css$/i,
				use: [
					{
						loader: MiniCSSExtractPlugin.loader,
					},
					{
						loader: 'css-loader',
					},
				],
			},
			{
				test: /\.less$/i,
				use: [
					{
						loader: MiniCSSExtractPlugin.loader,
					},
					{
						loader: 'css-loader',
					},
					{
						loader: 'less-loader',
					},
				],
			},

			{
				test: /\.scss$/i,
				use: [
					{
						loader: MiniCSSExtractPlugin.loader,
					},
					{
						loader: 'css-loader',
					},
					{
						loader: 'postcss-loader',
					},
					{
						loader: 'sass-loader',
					},
				],
			},
		],
	},
	optimization: {
		// splitChunks: {
		// 	cacheGroups: {
		// 		style: {
		// 			type: 'css/mini-extract',
		// 			test: /[\\/]style(\.module)?\.(sc|sa|c)ss$/,
		// 			chunks: 'all',
		// 			enforce: true,
		// 			name( _, chunks, cacheGroupKey ) {
		// 				const chunkName = chunks[ 0 ].name;
		// 				return `${ dirname(
		// 					chunkName
		// 				) }/${ cacheGroupKey }-${ basename( chunkName ) }`;
		// 			},
		// 		},
		// 		default: false,
		// 	},
		// },
	},
	plugins: [
		new RemoveEmptyScriptsPlugin(),
		new MiniCSSExtractPlugin( { filename: '[name].[contenthash:8].css' } ),
		new DependencyExtractionWebpackPlugin(),
		new WebpackBar(),
		...( args.mode === 'development' ? [
			new EslintPlugin( { extensions: [ 'js', 'jsx', 'ts', 'tsx' ] } ),
		] : [
			new CompressionPlugin(),
		] ),
		new WebpackManifestPlugin( {
			basePath: '',
			fileName: 'manifest.json',
			removeKeyHash: true,
			generate: ( seed, files ) => {
				return files.reduce( ( manifest, file ) => {
					if ( ! file.chunk ) {
						return manifest;
					}
					const entries = {};
					file.chunk.files.forEach( ( path ) => {
						let ext = path.split( '.' ).pop();
						switch ( ext ) {
							case 'js':
								ext = 'script';
								break;
							case 'css':
								ext = 'style';
								break;
							case 'php':
								ext = 'asset';
								break;
						}
						// else {
						// 	if ( ! entries[ ext ] ) {
						// 		entries[ ext ] = [];
						// 	}
						// 	entries[ ext ].push( path );
						// }
						entries[ ext ] = path;
					} );
					if ( file.chunk.name ) {
						manifest[ file.chunk.name ] = entries;
					}
					return manifest;
				}, seed );
			},
		} ),
	].filter( Boolean ),
	devtool: args.mode === 'development' ? 'source-map' : false,
} );

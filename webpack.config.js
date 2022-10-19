const EslintPlugin = require( 'eslint-webpack-plugin' );
const WebpackBar = require( 'webpackbar' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const { resolve } = require( 'path' );
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );

// eslint-disable-next-line no-unused-vars
module.exports = ( _, args ) => ( {
	entry: {
		customizer: resolve( process.cwd(), 'assets/js/customizer', 'index.js' ),
		'customizer-preview': resolve( process.cwd(), 'assets/js/customizer/preview', 'index.js' ),
		style: resolve( process.cwd(), 'assets/scss', 'style.scss' ),
	},
	output: {
		filename: '[name].js',
		path: resolve( process.cwd(), 'assets/dist' ),
	},
	resolve: {
		extensions: [ '.js', '.jsx' ],
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
		new MiniCSSExtractPlugin( { filename: '[name].css' } ),
		new DependencyExtractionWebpackPlugin(),
		new WebpackBar(),
		args.mode === 'development' ? new EslintPlugin( { extensions: [ 'js', 'jsx', 'ts', 'tsx' ] } ) : false,
	].filter( Boolean ),
	devtool: false,
} );

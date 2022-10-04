const EslintPlugin = require( 'eslint-webpack-plugin' );
const WebpackBar = require( 'webpackbar' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const { resolve, basename, dirname } = require( 'path' );
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const FixStyleOnlyEntriesPlugin = require( 'webpack-fix-style-only-entries' );

module.exports = () => ( {
	entry: {
		customizer: resolve( process.cwd(), 'assets/js/customizer', 'index.js' ),
		normalize: resolve( process.cwd(), 'assets/scss', 'normalize.scss' ),
		global: resolve( process.cwd(), 'assets/scss', 'global.scss' ),
		header: resolve( process.cwd(), 'assets/scss', 'header.scss' ),
		content: resolve( process.cwd(), 'assets/scss', 'content.scss' ),
		'page-header': resolve( process.cwd(), 'assets/scss', 'page-header.scss' ),
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
						loader: 'sass-loader',
					},
				],
			},
		],
	},
	optimization: {
		splitChunks: {
			cacheGroups: {
				style: {
					type: 'css/mini-extract',
					test: /[\\/]style(\.module)?\.(sc|sa|c)ss$/,
					chunks: 'all',
					enforce: true,
					name( _, chunks, cacheGroupKey ) {
						const chunkName = chunks[ 0 ].name;
						return `${ dirname(
							chunkName
						) }/${ cacheGroupKey }-${ basename( chunkName ) }`;
					},
				},
				default: false,
			},
		},
	},
	plugins: [
		new FixStyleOnlyEntriesPlugin(),
		new MiniCSSExtractPlugin( { filename: '[name].css' } ),
		new DependencyExtractionWebpackPlugin(),
		new EslintPlugin( {
			extensions: [ 'js', 'jsx', 'ts', 'tsx' ],
		} ),
		new WebpackBar(),
	].filter( Boolean ),
	devtool: false,
} );

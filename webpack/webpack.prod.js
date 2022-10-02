const EslintPlugin = require( 'eslint-webpack-plugin' );
const WebpackBar = require( 'webpackbar' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const { dirname, basename, resolve } = require( 'path' );
const CSSMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );

module.exports = () => ( {
	entry: {
		customizer: resolve( process.cwd(), 'assets/js/customizer', 'index.js' ),
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
		minimize: true,
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
		minimizer: [
			new CSSMinimizerPlugin(),
		],
	},
	plugins: [
		new MiniCSSExtractPlugin( { filename: '[name].css' } ),
		new DependencyExtractionWebpackPlugin(),
		new EslintPlugin( {
			extensions: [ 'js', 'jsx', 'ts', 'tsx' ],
		} ),
		new WebpackBar(),
	].filter( Boolean ),
	devtool: false,
} );

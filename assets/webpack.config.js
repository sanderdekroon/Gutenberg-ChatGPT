const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

const isDevelopment = process.env.ENV !== 'production';
const isWatching = process.env.WATCH !== 'false';

module.exports = {
	mode: isDevelopment ? 'development' : 'production',
	watch: isWatching,
	entry: {
		admin: './assets/src/js/admin.js',
	},
	output: {
		filename: isDevelopment ? 'js/[name].js' : 'js/[name].[contenthash].js',
		path: path.resolve( __dirname, './dist' ),
		assetModuleFilename: 'static/[name].[contenthash][ext][query]',
		clean: true,
	},
	externals: {
		jquery: 'jQuery',
	},
	devtool: isDevelopment ? 'eval-source-map' : 'source-map',
	plugins: [
		new MiniCssExtractPlugin( {
			filename: isDevelopment ?
				'css/[name].css' :
				'css/[name].[contenthash].css',
		} ),
	],
	module: {
		rules: [
			{
				test: /\.jsx?$/, // Javascript
				use: 'babel-loader',
				exclude: /[\\/]node_modules[\\/](?!(simple-line-icons)[\\/])/,
			},
			{
				test: /\.(sa|sc|c)ss$/, // (S)CSS
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: { sourceMap: isDevelopment },
					},
					{
						loader: 'postcss-loader',
						options: { sourceMap: isDevelopment },
					},
					{
						loader: 'sass-loader',
						options: { sourceMap: isDevelopment },
					},
				],
			},
		],
	},
	// @see https://webpack.js.org/configuration/resolve/#resolveextensions
	resolve: {
		extensions: [ '.js', '.jsx', '.scss' ],
	},
};

const path=require('path')
const {CleanWebpackPlugin}  = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const TerserJSPlugin=require('terser-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');

const ASSET_PATH = process.env.ASSET_PATH || '/dist/';

module.exports = (env, argv) => {
	const isDevMode=(argv.mode==='development')
	let config={
		entry:{
			app : ["./assets/css/app.css","./assets/js/app.js"]
		},

		output:{
			path: path.resolve('./apache/public/dist/'),
			publicPath: ASSET_PATH,
			//filename: isDevMode ? '[name].js' : '[name].[chunkhash:8].js'
			filename: '[name].js'
		},

		resolve:{
			alias:{
				'@': path.resolve('./assets/'),
				'@js': path.resolve('./assets/js'),
				'@css': path.resolve('./assets/css')
			}
		},

		devtool: isDevMode ? "cheap-module-eval-source-map" : false ,
		watch: isDevMode,

		module: {
			rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules|bower_components)/,
				use: {
			        loader: 'babel-loader',
			        /*options: {
			          presets: ['@babel/preset-env']
			        }*/
			    },
			},

			{
	        	test: /\.css$/,
	        	use: [MiniCssExtractPlugin.loader,'css-loader'],
	        },

	        {
		      	test: /\.(woff2?|eot|ttf|otf)$/,
		      	loader: 'file-loader'
		    },

		    {
		    	test: /\.(png|jpg|gif|svg)$/,
		        use: [{
		            loader: 'url-loader',
		            options: {
		              limit: 8192,
		              name: '[name].[hash:8].[ext]',
					  publicPath: './'
		            },
		        }],
		    },

	        ]

		},

		plugins:[
			new MiniCssExtractPlugin({
				//filename: isDevMode ? '[name].css' :'[name].[contenthash:8].css'
				filename: '[name].css',
			}),

		],

		optimization: {
			 minimizer: [new TerserJSPlugin({}),new OptimizeCSSAssetsPlugin({})],
		},



	}

	if(!isDevMode){
		config.plugins.push(new CleanWebpackPlugin())
	}

	return config;
};

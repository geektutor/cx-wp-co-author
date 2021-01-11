const path = require('path');
const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config");

module.exports = {
  ...defaultConfig,
	entry: {
		'post-sidebar': path.resolve( __dirname, 'assets/js/src', 'post-sidebar.js' ),
	},
	output: {
		filename: '[name].js',
		path: path.resolve( __dirname, 'assets/js/dist' ),
	},
	externals: {
		'react': React,
		'react-dom': ReactDOM,
	}
};
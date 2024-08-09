const defaultConfig = require("@wordpress/scripts/config/.eslintrc.js");

module.exports = {
	...defaultConfig,
	rules: {
		...defaultConfig.rules,
		'@wordpress/no-unsafe-wp-apis': 'off',
	},
};

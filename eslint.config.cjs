const defaultConfig = require('@wordpress/scripts/config/eslint.config.cjs');

module.exports = [
	{
		ignores: [ 'dist/**', '.export/**' ],
	},
	...defaultConfig,
	{
		settings: {
			'import/core-modules': [
				'@wordpress/block-editor',
				'@wordpress/blocks',
				'@wordpress/components',
				'@wordpress/compose',
				'@wordpress/data',
				'@wordpress/hooks',
				'@wordpress/i18n',
			],
		},
		rules: {
			'@wordpress/no-unsafe-wp-apis': 'off',
		},
	},
];

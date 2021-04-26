const purgecss = require("@fullhuman/postcss-purgecss")({
	content: ['**/*.php', 'scripts/*.js', 'scripts/**/*.js',],
	whitelist: [
		
	],
	whitelistPatterns: [/.is-$/],

  // Include any special characters you're using in this regular expression
  defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || []
});

module.exports = {
  plugins: [
		require("postcss-easy-import")({ prefix: "_" }),
    	require("postcss-import"),
		require("tailwindcss")(`${__dirname}/../../../../tailwind.config.js`),
		purgecss,
		require('autoprefixer'),
  ]
};

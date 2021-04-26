module.exports = {
  plugins: [
		require("postcss-easy-import")({ prefix: "_" }),
    require("postcss-import"),
    require("@tailwindcss/jit")(`${__dirname}/../../../../tailwind.config.js`),
    require("autoprefixer"),
  ]
};

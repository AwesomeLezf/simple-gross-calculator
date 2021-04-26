const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

const isProduction = process.env ? process.env.NODE_ENV === "production" : false;

module.exports = () => {
 	config = {
    entry: "./public/src/js/main.js",
    output: {
      path: path.resolve(__dirname, "..", "..", "dist"),
      filename: "bundle.js"
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: "[name].bundle.css"
      })
    ],
    module: {
      rules: [
        {
          test: /\.(sa|sc|c)ss$/,
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: "css-loader",
              options: {
                importLoaders: 1
              }
            },
            {
              loader: "postcss-loader",
              options: {
                sourceMap: "inline"
              },
              options: {
                postcssOptions: {
                  ident: "postcss",
                  config:
                    `${__dirname}/${
                      isProduction ? "production" : "development"
                    }/postcss.config.js`
                }
              }
            },
            {
              loader: "sass-loader"
            }
          ]
        }
      ]
    }
	};

	return config
};

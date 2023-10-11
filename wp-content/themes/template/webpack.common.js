const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    target: ['web', 'es5'],
  entry: {
    app: './src/js/index.js',
  },
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'dist'),
    clean: true,
  },
  module: {
      rules: [
          {
              enforce: 'pre',
              exclude: /node_modules/,
              test: /\.jsx$/,
              loader: 'eslint-loader'
          },
          {
              test: /\.jsx?$/,
              exclude: /node_modules/,
              loader: 'babel-loader',
          },
          {
              test: /\.s[ac]ss$/,
              use: [
                  MiniCssExtractPlugin.loader,
                  {
                      loader: "css-loader",
                      options: {
                          url: false,
                          sourceMap: true,
                      }
                  },
                  "sass-loader",
              ],
          },
      ]
  },
  plugins: [new MiniCssExtractPlugin()],
};
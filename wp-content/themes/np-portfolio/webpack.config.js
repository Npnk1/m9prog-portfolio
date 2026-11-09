/**
 * Webpack bouwt src/ naar dist/:
 *  - src/js/main.js   -> dist/main.js
 *  - src/scss/*.scss  -> dist/main.css (via sass-loader en MiniCssExtractPlugin)
 *  - lettertypen      -> dist/fonts/
 */
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

module.exports = (env, argv) => {
  const isProduction = argv.mode === 'production';

  return {
    entry: {
      main: './src/js/main.js',
    },
    output: {
      path: path.resolve(__dirname, 'dist'),
      filename: '[name].js',
      clean: true,
    },
    devtool: isProduction ? false : 'source-map',
    module: {
      rules: [
        {
          test: /\.s?css$/i,
          use: [
            MiniCssExtractPlugin.loader,
            { loader: 'css-loader', options: { sourceMap: !isProduction } },
            {
              loader: 'sass-loader',
              options: {
                sourceMap: !isProduction,
                sassOptions: {
                  // Geen BOM/@charset midden in de CSS: dat maakt de :root-regel van Bootstrap ongeldig
                  charset: false,
                  // Verbergt waarschuwingen uit Bootstrap zelf (niet uit onze eigen code)
                  quietDeps: true,
                  silenceDeprecations: ['mixed-decls'],
                },
              },
            },
          ],
        },
        {
          test: /\.(woff2?|ttf|otf)$/i,
          type: 'asset/resource',
          generator: { filename: 'fonts/[name][ext]' },
        },
        {
          test: /\.(png|jpe?g|gif|svg|webp)$/i,
          type: 'asset/resource',
          generator: { filename: 'images/[name][ext]' },
        },
      ],
    },
    plugins: [new MiniCssExtractPlugin({ filename: '[name].css' })],
    optimization: {
      minimizer: ['...', new CssMinimizerPlugin()],
    },
    performance: { hints: false },
  };
};

const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const fse = require('fs-extra');
const currentTask = process.env.npm_lifecycle_event;

/**
 * Run instance of this class after the Core Webpack Process to change the hashed scripts, vendors and styles in functions.php
 */
class RunAfterCompile {
  apply(compiler) {
    compiler.hooks.done.tap('Update functions.php', function () {
      const manifest = fse.readJsonSync('./dist/manifest.json');

      fse.readFile('./functions.php', 'utf8', function (err, data) {
        err ? console.log(err) : '';

        const scriptsRegEx = new RegExp("/dist/index.+?'", 'g');
        const vendorsRegEx = new RegExp("/dist/vendors.+?'", 'g');
        const cssRegEx = new RegExp("/dist/styles.+?'", 'g');

        let result = data
          .replace(scriptsRegEx, `/dist/${manifest['index.js']}'`)
          .replace(vendorsRegEx, `/dist/${manifest['vendors.js']}'`)
          .replace(cssRegEx, `/dist/${manifest['index.css']}'`);

        fse.writeFile('./functions.php', result, 'utf8', function (err) {
          if (err) return console.log(err);
        });
      });
    });
  }
}

let scssConfig = {
  test: /\.s[ac]ss$/i,
  use: [
    'style-loader', // Creates `style` nodes from JS strings
    {
      loader: 'css-loader', // Translates CSS into CommonJS
      options: {
        url: true
      }
    },
    'resolve-url-loader', // Resolve relative url() in css
    {
      loader: 'sass-loader', // Compiles Sass to CSS
      options: {
        sourceMap: true
      }
    }
  ]
};

/**
 * Basic Configuration (both for Dev and Build)
 */
let config = {
  entry: {
    index: './src/js/index.js'
  },
  plugins: [],
  module: {
    rules: [
      scssConfig,
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      {
        test: /\.(png|svg|jpg|jpeg|gif)$/i,
        type: 'asset/resource'
      },
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/i,
        type: 'asset/resource'
      }
    ]
  }
};

/**
 * DEV specific configuration
 */
if (currentTask === 'start') {
  (config.mode = 'development'), (config.devtool = 'source-map');
  config.devServer = {
    static: {
      directory: path.join(__dirname, 'dist')
    },
    compress: true,
    port: 3000,
    hot: true,
    headers: {
      'Access-Control-Allow-Origin': '*'
    },
    allowedHosts: ['all'],
    watchFiles: ['./**/*.php']
  };
  config.output = {
    filename: 'bundle.js'
  };
}

/**
 * Build specific configuration
 */
if (currentTask === 'build') {
  scssConfig.use.shift(); // style-loader entfernen
  scssConfig.use.unshift(MiniCssExtractPlugin.loader);

  config.mode = 'production';
  config.output = {
    filename: '[name].[chunkhash].js',
    chunkFilename: '[name].[chunkhash].js',
    path: path.resolve(__dirname, 'dist'),
    assetModuleFilename: 'assets/[name][ext][query]'
  };
  config.optimization = {
    splitChunks: {
      chunks: 'all',
      name: 'vendors'
    }
  };
  config.plugins.push(
    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({ filename: 'styles.[chunkhash].css' }),
    new WebpackManifestPlugin({ publicPath: '' }),
    new RunAfterCompile()
  );
}

module.exports = config;

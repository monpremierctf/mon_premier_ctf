/**
 * Copyright (c) 2018 The xterm.js authors. All rights reserved.
 * @license MIT
 *
 * This file is the entry point for browserify.
 */

const path = require('path');
const webpack = require('webpack');


// Build/watch client source
const clientConfig = {
  entry: path.resolve(__dirname, 'client.ts'),
  devtool: 'inline-source-map',
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: 'ts-loader',
        exclude: /node_modules/
      },
      {
        test: /\.js$/,
        use: ["source-map-loader"],
        enforce: "pre"
      }
    ]
  },
  resolve: {
    extensions: [ '.tsx', '.ts', '.js' ]
  },
  output: {
    filename: 'client-bundle.js',
    path: path.resolve(__dirname, 'dist')
  },
  mode: 'development',
  watch: true
};
const compiler = webpack(clientConfig);

compiler.run((err, stats) =>{
  console.log("Compiled");  
});

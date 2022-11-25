const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const WebpackBuildNotifierPlugin = require("webpack-build-notifier");

module.exports = function (config) {
  return {
    ...config,
    ignoreWarnings: [
      {
        // Change this to fit your needs
        module: /node_modules\/@walletconnect/,
      },
      {
        // Change this to fit your needs
        module: /node_modules\/@particle-network/,
      },
      {
        // Change this to fit your needs
        module: /node_modules\/@solana/,
      },
      {
        // Change this to fit your needs
        module: /node_modules/,
      },
    ],
    module: {
      ...config.module,
      rules: [
        ...config.module.rules,
        {
          test: /\.(m?js|ts)$/,
          enforce: "pre",
          use: ["source-map-loader"],
        },
      ],
    },
    resolve: {
      ...config.resolve,
      fallback: {
        stream: require.resolve("stream-browserify"),
        crypto: require.resolve("crypto-browserify"),
      },
    },
    output: {
      ...config.output,
      filename: "static/js/[name].min.js",
      chunkFilename: "static/js/[name].min.js",
      path: path.resolve(__dirname, "build"),
    },
    plugins: [
      ...config.plugins,
      new MiniCssExtractPlugin({
        filename: "static/css/[name].min.css",
      }),
      new WebpackBuildNotifierPlugin({
        title: "React Login Built",
        suppressSuccess: true,
      }),
    ],
  };
};

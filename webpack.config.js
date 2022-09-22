const path = require("node:path")
const fg = require("fast-glob")
const { merge } = require("webpack-merge")
const rimraf = require("rimraf")

const defaultConfig = require("@wordpress/scripts/config/webpack.config")

const getBlocks = () => {
  const blockPaths = fg.sync("./custom-blocks/**/*.+(ts|tsx|js|jsx)")

  const blocks = blockPaths.map((p) => {
    const res = p.match(/\.\/custom-blocks\/(.*)\.(j|t)sx?$/)

    const fileName = res?.[1] ?? null

    if (!fileName) return null

    return {
      name: fileName,
      path: p,
    }
  })

  return blocks
}

module.exports = function () {
  rimraf.sync(path.resolve(__dirname, "build"))

  return [
    merge(defaultConfig, {
      entry: { index: "./src/index.ts" },
      output: {
        path: path.resolve(__dirname, "build"),
      },
    }),
    ...getBlocks()
      .filter(Boolean)
      .map((block) =>
        merge(defaultConfig, {
          entry: { [block.name]: block.path },
          output: {
            path: path.resolve(__dirname, "build"),
          },
        }),
      ),
  ]
}

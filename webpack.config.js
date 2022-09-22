const path = require("node:path")
const fg = require("fast-glob")
const { merge } = require("webpack-merge")
const rimraf = require("rimraf")

const defaultConfig = require("@wordpress/scripts/config/webpack.config")

const getBlocks = () => {
  const blockPaths = fg.sync("./src/blocks/**/index.tsx")

  const blocks = blockPaths.map((p) => {
    const res = p.match(/\.\/src\/blocks\/(.*)\/(.*).tsx?/)
    const folderName = res?.[1] ?? null

    if (!folderName) return null

    const fileName = res?.[2] ?? null

    if (fileName !== folderName && fileName !== "index") return null

    return {
      name: folderName,
      path: p,
    }
  })

  return blocks
}

module.exports = function () {
  rimraf.sync(path.resolve(__dirname, "dist"))

  return [
    merge(defaultConfig, {
      entry: { index: "./src/index.ts" },
      output: {
        path: path.resolve(__dirname, "dist"),
      },
    }),
    ...getBlocks()
      .filter(Boolean)
      .map((block) =>
        merge(defaultConfig, {
          entry: { [block.name]: block.path },
          output: {
            path: path.resolve(__dirname, "dist", block.name),
          },
        }),
      ),
  ]
}

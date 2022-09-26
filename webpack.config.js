const path = require("node:path")
const fg = require("fast-glob")
const { merge } = require("webpack-merge")
const rimraf = require("rimraf")

const defaultConfig = require("@wordpress/scripts/config/webpack.config")

const getBlocks = () => {
  const blockPaths = fg.sync("./src/blocks/**/*.+(ts|tsx|js|jsx)")

  const blocks = blockPaths.map((p) => {
    const res = p.match(/\.\/src\/blocks(\/.*)+\.(?:j|t)sx?$/)

    const route = res?.[1] ?? null

    if (!route) return null

    const routes = route.slice(1).split("/")

    const folderName = routes?.[0] ?? null
    const fileName = routes?.at(-1) ?? null

    if (!folderName) return null

    if (fileName !== folderName && fileName !== "index") return null

    return {
      name: folderName,
      path: p,
    }
  })

  console.log(blocks)

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
            path: path.resolve(__dirname, "build", block.name),
          },
        }),
      ),
  ]
}

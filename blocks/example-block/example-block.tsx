import { registerBlockType } from "@wordpress/blocks"

registerBlockType("custom-blocks/example-block", {
  title: "Example Block",
  category: "widgets",
  attributes: {},
  edit: () => <div>example block edit</div>,
  save: () => <div>example block save</div>,
})

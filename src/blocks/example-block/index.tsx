import { registerBlockType } from "@wordpress/blocks"

registerBlockType("blocks/exampleblock", {
  title: "Example Block",
  category: "custom",
  attributes: {},
  edit: Edit,
  save: Save,
})

function Edit() {
  return <div>example block edit</div>
}

function Save() {
  return <div>example block save</div>
}

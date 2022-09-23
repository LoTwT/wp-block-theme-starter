# wp-block-theme-starter

A template for Wordpress block theme development.

Be careful that it's simple and crude, it may not be capable for production.

## use

> hmr seems to have some issues... so it may need you to create a new chrome to find if your code change has effect

the whole folder should be in `wp-content/themes`

custom blocks should be created in `/blocks`

add import in `functions.php` such as `new JSXBlock(example-block);`

```bash
# dev
pnpm start

# build
pnpm build
```

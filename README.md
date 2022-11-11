# A skeleton for a Wordpress theme with Webpack

This is a Skeleton Wordpress Theme with webpack, Hot Module Reload for JS and SCSS Files, auto reload for PHP-Files, which makes developing Wordpress themes really simple and fun!

## Installation

1. Create a Wordpress Installation locally on your computer (e.g. with <https://localwp.com/>)
2. Clear all content from wp-content and instead pack the content of this repo into the folder (without the container-folder of the repo, so all files directly into wp-content)
3. Open Terminal within my-theme Folder
4. Install the npm-Packages as defined in package.json (this runs with Node 16+, check your version)
5. Go into functions.php and define in row 15, which local URL you are using for your Wordpress Instance, to get the development-files when you are in Dev-Mode
6. run "npm start" to Enter Developer-Mode
   - **It will do a hot reload (without the page being reloaded) on every change in JS oder SCSS**
   - **It will do an automatic reload on every change within a PHP file**
7. run "npm run build" to build your dist-Files. A Folder "dist" with the bundled js-Scripts, js-Vendors, css-Styles and used assets (fonts and images) will be created
8. After building the Dist-Files, webpack will automatically change the linked scripts and styles in functions.php as those are hashed values.

## SASS

- You can use the predefined functions and mixins to use media-queries or set font-sizes
- font-sizes: Use "font-size(_size_)". Sizes are defined in _"./css/base/\_02variables.scss"_
- media-queries: Use "@include media-mobile-ls", "@include media-tablet" or "@include media-desktop" to include your code for these sizes. Mixins are defined in _"./css/base/\_03mixins.scss"_ and sizes are defined in _"\_02variables.scss"_
- Mobile First: This theme uses the Mobile First principal: The code directly defined within the selector will be added to every screen. If you want additional code to larger screens (e.g. grid-templates) you can put this into media-queries
- Put your own code into the modules-Folder

## JS

- All scripts are added as "defer" scripts
- Webpack will bundle everything and give two output files: Vendors (third party stuff) and your own scripts (bundled.js)
- you can use ES6 Syntax within your modules to import/export stuff.

## Wordpress

This repo is primarily made for a working webpack-environment with HMR, Sass, JS inside a wordpress Site. The Wordpress settings are very basic, most of the files only contain title and content. Here your skills can shine!

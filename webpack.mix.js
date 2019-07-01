const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/**************************************
 * Admin Application
 **************************************/
const adminAssets = 'Modules/Admin/Resources/assets/';

mix.copyDirectory('node_modules/font-awesome/fonts', 'public/admin/fonts');
mix.copyDirectory('node_modules/simple-line-icons/fonts', 'public/admin/fonts');

mix.sass(adminAssets + 'scss/app.scss', 'public/admin/css/app.css');

mix.styles([
  'node_modules/select2/dist/css/select2.min.css',
  'node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css',
  'node_modules/@coreui/icons/css/coreui-icons.min.css',
  'node_modules/font-awesome/css/font-awesome.min.css',
  'node_modules/@coreui/coreui/dist/css/coreui.min.css',
  'node_modules/simple-line-icons/css/simple-line-icons.css',
  'node_modules/dropzone/dist/min/dropzone.min.css',
], 'public/admin/css/vendor.css');

mix.combine([
  'node_modules/jquery/dist/jquery.min.js',
  'node_modules/jquery-ui-dist/jquery-ui.min.js',
  'node_modules/popper.js/dist/umd/popper.min.js',
  'node_modules/bootstrap/dist/js/bootstrap.min.js',
  'node_modules/@coreui/coreui/dist/js/coreui.min.js',
  'node_modules/jquery.maskedinput/src/jquery.maskedinput.js',
  'node_modules/select2/dist/js/select2.min.js',
  'node_modules/dropzone/dist/min/dropzone.min.js',
  adminAssets + 'js/select2Module.js',
  adminAssets + 'js/commonModule.js',
  adminAssets + 'js/listingModule.js',
  adminAssets + 'js/formModule.js',
  adminAssets + 'js/dropZoneModule.js',
  adminAssets + 'js/multiStepSelectModule.js',
  adminAssets + 'js/imageUploadModule.js',
  adminAssets + 'js/app.js'
], 'public/admin/js/app.js');
/****** END Admin Application **********/


/**************************************
 * Frontend Application
 **************************************/
const frontendAssets = 'Modules/Frontend/Resources/assets/';

mix.copyDirectory(frontendAssets + 'img', 'public/frontend/img');

mix.copyDirectory('node_modules/font-awesome/fonts', 'public/frontend/fonts');
mix.copyDirectory('node_modules/simple-line-icons/fonts', 'public/frontend/fonts');
mix.copyDirectory('node_modules/elegant-icons/fonts', 'public/frontend/css/fonts');
mix.copyDirectory('node_modules/flag-icon-css/flags', 'public/frontend/flags');

mix.sass(frontendAssets + 'scss/app.scss', 'public/frontend/css/app.css');

mix.styles([
  'node_modules/font-awesome/css/font-awesome.min.css',
  'node_modules/simple-line-icons/css/simple-line-icons.css',
  'node_modules/flag-icon-css/css/flag-icon.min.css',
  'node_modules/elegant-icons/style.css',
  'node_modules/bootstrap/dist/css/bootstrap.min.css',
  'node_modules/bootstrap/dist/css/bootstrap-grid.css',
  'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
  'node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css',
  'node_modules/slick-carousel/slick/slick.css',
  'node_modules/slick-carousel/slick/slick-theme.css',
], 'public/frontend/css/vendor.css');

mix.combine([
  'node_modules/jquery/dist/jquery.min.js',
  frontendAssets + 'vendor/image-dropdown/jquery.dd.min.js',
  'node_modules/popper.js/dist/umd/popper.min.js',
  'node_modules/bootstrap/dist/js/bootstrap.min.js',
  'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
  'node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.js',
  'node_modules/slick-carousel/slick/slick.min.js',
  frontendAssets + 'js/commonModule.js',
  frontendAssets + 'js/widgets/CategoriesModule.js',
  frontendAssets + 'js/productModule.js',
  frontendAssets + 'js/app.js',
  'node_modules/jquery.cookie/jquery.cookie.js',
  frontendAssets + 'js/cartModule.js'
], 'public/frontend/js/app.js');
/****** END Frontend Application **********/

if (mix.inProduction()) {
  mix.version();
}

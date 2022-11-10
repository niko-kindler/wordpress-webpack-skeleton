/**
 * Import Styles
 */
import '../css/style.scss';

/**
 * Import own modules
 */
import './modules/my-module.js';
import MyClass from './modules/MyClass.js';

const myInstance = new MyClass();

/**
 * Enable Hot Module Reload for Dev
 */
if (import.meta.webpackHot) {
  import.meta.webpackHot.accept();
}

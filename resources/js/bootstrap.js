import _ from 'lodash';

window._ = _;

/**
 * jQuery and Bootstrap 4 JavaScript dependencies.
 */
import $ from 'jquery';
import Popper from 'popper.js';
import 'bootstrap';

window.Popper = Popper;
window.$ = window.jQuery = $;

/**
 * Axios HTTP client.
 */
import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

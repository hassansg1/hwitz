/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue").default;

import router from "./router";
import App from "./layouts/App.vue";
import Gate from "./Gate";
import axiosPlugin from "./axiosPlugin";
import { Form } from "vform";
import { HasError, AlertError } from "vform/src/components/bootstrap5";
import globalMixin from "./globalMixin";
import * as alert from "./alert";
import store from "./store";
// import pagination from "../../pagination.vue";
import CKEditor from "@ckeditor/ckeditor5-vue2";

Vue.use(axiosPlugin);
Vue.mixin(globalMixin);
global.jQuery = require("jquery");
var $ = global.jQuery;
window.$ = $;
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
let gate = new Gate(window.vueAuth);
let authRolePermission = gate.getAuthRolesPermissions();
Vue.prototype.$gate = authRolePermission;
Vue.use(CKEditor);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
  "example-component",
  require("./components/ExampleComponent.vue").default
);
Vue.component("pagination", require("../js/components/pagination.vue").default);
Vue.component("multiselect", require("vue-multiselect").default);
Vue.component("Sorting", require("../js/components/sorting.vue").default);
Vue.component(
  "entriesPerPage",
  require("../js/components/utils/entriesPerPage.vue").default
);
Vue.component("Modal", require("../js/components/utils/modal.vue").default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
console.log("here");
const app = new Vue({
  router,
  store,
  el: "#app",
  render: (h) => h(App),
});

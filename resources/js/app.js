/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

// for the moment.js
import moment from "moment";

// for the vform package
import { Form, HasError, AlertError } from "vform";

import Gate from "./Gate";
// this is how the $gate variable will available in all location
Vue.prototype.$gate = new Gate(window.user);

// for the vue-progressbar
import VueProgressBar from "vue-progressbar";

// for the sweetalert2
import swal from "sweetalert2";

window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

// for the pagination
Vue.component("pagination", require("laravel-vue-pagination"));

import VueRouter from "vue-router";
Vue.use(VueRouter);

let routes = [
    {
        path: "/dashboard",
        component: require("./components/Dashboard.vue").default
    },
    {
        path: "/home",
        component: require("./components/Dashboard.vue").default
    },
    {
        path: "/profile",
        component: require("./components/Profile.vue").default
    },
    {
        path: "/users",
        component: require("./components/Users.vue").default
    },
    {
        path: "/developer",
        component: require("./components/Developer.vue").default
    },
    {
        path: "*",
        component: require("./components/NotFound.vue").default
    }
];

const router = new VueRouter({
    mode: "history",
    routes // short for `routes: routes`
});

// capitalize the text
Vue.filter("capitalize", text => {
    if (!text) return "";
    text = text.toString();
    return text.charAt(0).toUpperCase() + text.slice(1);
});

// show date with nice format using moment.js
Vue.filter("showDate", function(dateText) {
    return moment(dateText).format("MMMM Do YYYY");
});

// vue-progressbar
Vue.use(VueProgressBar, {
    color: "#bffaf3",
    failedColor: "#874b4b",
    thickness: "5px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300
    },
    autoRevert: true,
    location: "top",
    inverse: false
});

// sweetalert2
window.swal = swal;

// for toast
const toast = swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000
});
window.toast = toast;

// for the global custom event handling
window.Fire = new Vue();

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
    "passport-clients",
    require("./components/passport/Clients.vue").default
);

Vue.component(
    "passport-authorized-clients",
    require("./components/passport/AuthorizedClients.vue").default
);

Vue.component(
    "passport-personal-access-tokens",
    require("./components/passport/PersonalAccessTokens.vue").default
);

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);

Vue.component("not-found", require("./components/NotFound.vue").default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router,
    data: {
        search: ""
    },
    methods: {
        // wait for 2 seconds and then fire an event
        searchText: _.debounce(() => {
            Fire.$emit("searching");
        }, 1000)
    }
});

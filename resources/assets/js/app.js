
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
require('materialize-css');

$(document).ready(function () {
    $(".dropdown-trigger").dropdown();

});


window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue';
import VueRouter from 'vue-router';
import CodeComponent from './components/pages/CodeComponent.vue';

Vue.use(VueRouter);

let Navbar = require('./components/inc/Navbar.vue');
let Sidebar = require('./components/inc/Sidebar.vue');
let Contents = require('./components/inc/Contents.vue');
let Editor = '';

const routes = [
    {path: '/preview', component: require('./components/pages/PreviewComponent.vue')}
];


let router = new VueRouter({
    routes // short for `routes: routes`
});


const app = new Vue({
    el: '#app',
    router,
    data() {
        return {
            contents: '',
            hasCode: true,
            themeType: AppSettings.theme_type,
            theme: AppSettings.theme
        }
    },
    components: {
        Navbar,
        Sidebar,
        Contents
    },
    watch: {
        contents() {

        }
    },
    mounted() {
        console.log("Hello");
    },
    methods: {
        getEditorValue() {

        }
    }
});

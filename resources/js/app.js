require('./bootstrap');
//window.Vue = require("vue");
//import  * as Vue from "vue";
//import { createApp } from 'vue';
import Vue from "vue/dist/vue.js";
//import Vue from 'vue';
window.Vue = require('vue/dist/vue.js');
import axios from "axios";
import router from "vue-router";
import toasted from "vue-toasted";
import VModal from "vue-js-modal";
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
Vue.prototype.$axios = axios;
//import { Dialog, Select, Option, TimePicker, Button } from "element-ui";
//import "element-ui/lib/theme-chalk/index.css";
//import lang from "element-ui/lib/locale/lang/ja";
//import locale from "element-ui/lib/locale";
// ホーム画面

/*
new Vue(
    "home-component",
    require("./components/HomeComponent.vue")
  ).$mount('#app')
*/

//import HomeComponent from "./components/HomeComponent.vue"
//import Mmake from "./components/Mmake.vue"
Vue.component("home-component", require("./components/HomeComponent.vue").default);
Vue.component("m-make", require("./components/Mmake.vue").default);
Vue.component("quotations-top", require("./components/Quotations.vue").default);
Vue.component("quotations-department", require("./components/QuotationsDepartment.vue").default);
Vue.component("quotations-binding", require("./components/QuotationsBinding.vue").default);
Vue.component("quotations-cost", require("./components/QuotationsCost.vue").default);
Vue.component("quotations-parts", require("./components/QuotationsParts.vue").default);
Vue.component("mit-parts", require("./components/Parts.vue").default);
Vue.component("out-sourcing", require("./components/Outsourcing.vue").default);
Vue.component("popup-print", require("./components/PopupPrint.vue").default);
Vue.component("quotations-search", require("./components/QuotationsSearch.vue").default);
Vue.component("quotations-doc", require("./components/QuotationsDoc.vue").default);

Vue.component("backup-logs", require("./components/BackupLogs.vue").default);


Vue.component("base-cnt", require("./components/Base.vue").default);

/*
var engHello =  {
    template: '<p>Hello {{ name }}!</p>',
    data(){
      return {
        name: "BBB"
      }
    }
}
*/

const vm = new Vue({
    el: "#app",
    /*
    components: {
        'home-component': {
            render: h => h(HomeComponent)
        },
        'm-make': {
            template: '<div>m-makeコンポーネント</div>'
        },
    },
    */
    //template: '<h1>日本語</h1>',
    // render: h => h(App),
    router // ルーティングの定義を読み込む
    // components: { App }, // ルートコンポーネントの使用を宣言する
    // template: "<App />" // ルートコンポーネントを描画する
});








import Vue from 'vue';
import App from './App.vue';

//  Axios  
import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios.create({
  baseURL: stph_rhd_getBaseUrlFromBackend(),
}))

//  Bootstrap Vue
import { BootstrapVue} from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)

//  Mixin
import mixin from '../mixin'
Vue.mixin(mixin)  

//  Create Vue Instance and mount our module page container
new Vue({
    render: h => h(App)
  })
.$mount('#STPH_DASHBOARD_RENDER');
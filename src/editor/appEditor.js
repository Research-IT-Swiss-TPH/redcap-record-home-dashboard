import Vue from 'vue';
import App from './App.vue';

//  Vuex Storage
import store from '../store'

//  Axios  
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios.create({
  baseURL: getBaseUrlFromBackend()
}))

//  Bootstrap Vue
import { BootstrapVue} from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)

//  Create Vue Instance and mount our module page container
new Vue({
  render: h => h(App),
  store
})
.$mount('#appEditor');


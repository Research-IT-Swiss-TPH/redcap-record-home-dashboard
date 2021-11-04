import Vue from 'vue';
import App from './App.vue';

//  Axios  
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios.create({
    baseURL: stph_rhd_getBaseUrlFromBackend()
  }))

//  Create Vue Instance and mount our module page container
new Vue({
    render: h => h(App)
  })
  .$mount('#STPH_DASHBOARD_RENDER');
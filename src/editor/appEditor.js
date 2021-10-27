import Vue from 'vue';
import App from './App.vue';

//  Oruga UI library
import Oruga from '@oruga-ui/oruga'
import '@oruga-ui/oruga/dist/oruga.css'
import '@oruga-ui/oruga/dist/oruga-full-vars.css'
import OrugaConfig from '../oruga'
Vue.use(Oruga, OrugaConfig)


//  Create Vue Instance and mount our module page container
new Vue({
  render: h => h(App)
})
.$mount('#appEditor');


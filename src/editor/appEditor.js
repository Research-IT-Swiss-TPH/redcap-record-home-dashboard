import Vue from 'vue';
import App from './App.vue';


//  Create Vue Instance and mount our module page container
new Vue({
  render: h => h(App)
})
.$mount('#appEditor');


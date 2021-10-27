import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        count: 0,
        selection: null
    },
    mutations: {
        initialiseStore(state) {
            if (localStorage.getItem('selection')) {
                state.selection = JSON.parse(localStorage.getItem('selection'))
            }            
        },
        increment (state) {
          state.count++
        },
        select (state, target) {
            localStorage.setItem('selection', JSON.stringify(target))
            state.selection = target
        },
        reset_select (state) {
            state.selection = null
            localStorage.removeItem('selection')
        }
    }
 })
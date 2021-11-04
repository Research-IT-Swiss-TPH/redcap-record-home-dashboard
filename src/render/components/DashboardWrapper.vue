<template>
  <div>
       <b-overlay variant="light"
            blur="50px"
            spinner-variant="dark"
            spinner-small
            opacity="0.95"
            :show="isOverlayed" 
            rounded="sm">
            <template #overlay>
                <loader />
            </template>

            <div class="render-wrapper p-4">
                  <div v-for="(row, index) in rows" :key="index" class="row mb-1 mt-2">
                      
                      <div v-for="(col, index) in row.columns" :key="index" class="col">
                          <dashboard-element 
                            v-for="(element, index) in col.elements" :key="index" 
                            :element="element" />
                            
                            <div v-if="col.elements.length == 0">
                                
                            </div>

                      </div>
                      
                      
                  </div>
            </div>

       </b-overlay>
  </div>
</template>

<script>
import loader from '../../loader.vue'
import DashboardElement from './DashboardElement.vue'
export default {
    components: {
        DashboardElement,
        loader
    },
    data() {
        return {
            isOverlayed: true,
            rows: []
        }
    },
    methods: {
        async loadDashboardData() {

          this.axios({
            params: {
              action: 'get-dashboard-data'
            }
          })
          .then( response => {
            let json = response.data;
            setTimeout(()=> {
                this.rows = JSON.parse(json)
                
            }, 500)              
          })
          .catch(e => {
            this.toastError(e)
          })
          .finally(()=> {
            setTimeout(()=> {
                this.isOverlayed = false
            }, 500)              
          })
        },
    },

    mounted() {
        this.loadDashboardData()
    }
}
</script>

<style>
    .render-wrapper {
        min-height: 600px;
        width: 100%;
        background: #ecf0f1;
        border-radius: 3px;        
    }
</style>
<template>
  <div>

    <div v-if="element.type == 'text'">
        <h2 class="lead mt-3">{{ element.content.title }}</h2>
    </div>

    <div v-else-if="element.type=='link'">
      <div class="mt-1">
        <b-skeleton v-if="isRendering" type="button" height="37.5px" width="100%"></b-skeleton>
        <b-button 
            v-else-if="!isRendering && render.length > 0"
            :href="render"
            :target="element.content.target || '_self'"
            variant="info"
            class="text-center"
            block
            size="lg"
         >
         <i v-if="element.content.icon && element.content.icon.length > 0" :class="'fa fa-'+element.content.icon"></i>
        {{ element.content.title }}</b-button>
        <b-alert v-else show variant="warning"><b>Error:</b> Invalid URL</b-alert>
      </div>
 
    </div>

    <div v-else-if="element.type=='list'">
        <b-list-group>
            <b-list-group-item
                class="d-flex justify-content-between align-items-center"
                v-for="(li, idx) in element.content" :key="li.title" >

                <template v-if="isRendering">
                  <b-skeleton width="75px"></b-skeleton>
                  <b-skeleton width="50px"></b-skeleton>
                </template>
                
                <template v-else>
                 <small class="font-weight-bold">{{ li.title}}:</small>
                 <span>{{ render[idx] }}</span>
                </template>

        </b-list-group-item>
        </b-list-group>

    </div>

    <div v-else-if="element.type == 'table'">
        <template v-if="isRendering">
          <b-skeleton-table
            :rows="3"
            :columns="element.content.columns.length"
            :table-props="{ striped: true }"
          ></b-skeleton-table>
        </template>
        <template v-else>
          <b-table striped hover :items="render"></b-table>
        </template>
    </div>

  </div>
  
</template>

<script>
export default {
    props: [
        'element'
    ],
     data() {
      return {
        
        isRendering: true,
        render: "",
        error: ""
      }
    },
    methods: {
      async renderElement() {
        
          this.axios({
            params: {
              action: 'render-element-content',
              type: this.element.type,
              content: JSON.stringify(this.element.content),
              params: stph_rhd_getBaseParametersFromBackend()
            }
          })
          .then( response => {
            let json = response.data
            this.render = response.data            
    
          })
          .catch(error => {
            console.log(error)
          })
          .finally(()=> {
            setTimeout(()=> {
              this.isRendering = false
            }, 500)              
          })


      }
    },
    mounted() {

      if( ["link", "list", "table"].includes(this.element.type)) {
        this.renderElement()
      }      
      
    }
}
</script>

<style scoped>
  .alert.alert-warning {
    padding: .5rem 1rem;
  }
</style>
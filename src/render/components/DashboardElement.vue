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
                v-for="li in element.content" :key="li.title" >
                 <small class="font-weight-bold">{{ li.title}}:</small>
                 <span>{{ li.value }}</span>
        </b-list-group-item>
        </b-list-group>

    </div>

    <div v-else-if="element.type == 'table'">
        <b-table striped hover :items="items"></b-table>
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
        items: [          
          { contact_date: '07-02-2020', communication_channel: 'see comment', reached: 'see comment', substudy: 'CHRONOS', fw_info: 'Edit Address Check Information', contact_later: '', comment: 'Fake participant created', health_info: 'rofl', edit_user: 'vermth' },
          { contact_date: '12-06-2020', communication_channel: 'paper', reached: 'paarticipant reached', substudy: '' ,fw_info: 'Paper Questionaires or other documents received back', contact_later: '', comment: 'QAB', health_info: 'QAB', edit_user: 'vermth' },
          { contact_date: '01-10-2021', communication_channel: 'paper', reached: 'foo', fw_info: 'foo', substudy: '',contact_later: '', comment: 'lmao', health_info: 'rofl', edit_user: 'dnmda' },
        ],
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
              content: this.element.content,
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
      if(this.element.type != "text") {
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
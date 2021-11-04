<template>
  <div>

    <div v-if="element.type == 'text'">
        <h2 class="mt-2 lead">{{ element.content.title }}</h2>
    </div>

    <div v-else-if="element.type=='link'">
        <b-button 
            :href="element.content.url"
            variant="info"
            class="text-center mt-1"
            block
            size="lg"
         >
        {{ element.content.title }}</b-button>        
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
          { contact_date: '01-10-2021', communication_channel: 'paper', reached: 'foo', fw_info: 'foo', substudy: '',contact_later: '', comment: 'lmao', health_info: 'rofl', edit_user: 'dnmda' }


        ]
      }
    }
}
</script>

<style scroped>
    .row {

    }
</style>
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
          { age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
          { age: 21, first_name: 'Larsen', last_name: 'Shaw' },
          { age: 89, first_name: 'Geneva', last_name: 'Wilson' },
          { age: 38, first_name: 'Jami', last_name: 'Carney' }
        ]
      }
    }
}
</script>

<style scroped>
    .row {

    }
</style>
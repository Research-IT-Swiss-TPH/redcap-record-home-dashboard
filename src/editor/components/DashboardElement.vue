<template>
  <div>
      <div class="card mb-2 mt-2" v-for="(element, index) in elements" :key="index" >
        <div class="card-header">
            <div class="float-left">
                <small class="text-muted">Element - # {{ index + 1}} </small>
                <span class="badge badge-secondary text-uppercase pr-1">{{ element.type }}</span>
            </div>

            <div class="editor-row-menu float-right">

                <b-button  @click="handleEmit('open-modal-element', index)" size="xs">
                    <i class="fa fa-edit"></i>
                </b-button>                    

                <b-button  @click="handleEmit('delete-element', index)" size="xs">
                    <i class="fa fa-trash-alt"></i>
                </b-button>

            </div>
        </div>
          <div class="card-body">
              <render-element
                :element="element"
            />
          </div>
      </div>
  </div>
</template>

<script>
import RenderElement from './RenderElement.vue'
export default {
    components: {
        RenderElement
    },
    props: [
        'elements',
        'c_id',
        'r_id'
    ],
    methods: {
        handleEmit(event, e_id) {            
            this.$emit(event, {
                "c_id": this.c_id,
                "r_id": this.r_id,
                "e_id": e_id
            })
        }
    }
}
</script>

<style scoped>
    .card {
        margin-bottom: 15px;
        max-width: 350px;
        margin: 0 auto;
    }
</style>>

</style>
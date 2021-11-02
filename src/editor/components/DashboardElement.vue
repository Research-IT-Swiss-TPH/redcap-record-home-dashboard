<template>
  <div>
      <div class="card mb-2 mt-2" v-for="(element, index) in elements" :key="index" >

          <div class="card-body">

            <div class="clearfix mb-1" >
                <div class="float-left">
                    <span class="badge badge-light text-uppercase p-1"> <i :class="iconClass(element.type)"></i>  {{ element.type }}</span>
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
            <render-element
                :element="element" />
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
        'r_id',
        'col_length'
    ],
    methods: {
        handleEmit(event, e_id) {            
            this.$emit(event, {
                "c_id": this.c_id,
                "r_id": this.r_id,
                "e_id": e_id
            })
        },
        iconClass(type) {
            switch (type) {
                case "text":
                    return "fa fa-align-left"
                    break;
                case "link":
                    return "fa fa-link"
                    break;
                case "list":
                    return "fa fa-th-list"
                    break;
                case "table":
                    return "fa fa-tabe"
                    break;                                                            
            
                default:
                    return "fa fa-align-left"
                    break;
            }
        }
    }
}
</script>

<style scoped>
    .card {
        margin-bottom: 15px;
        margin: 0 auto;
    }
    .card-body {
        padding: 0.75rem!important;
    }
</style>>

</style>
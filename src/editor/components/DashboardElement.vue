<template>
  <div>
    <draggable 
            :list="elements"
            ghost-class="ghost-element"            
            :group="{name: 'elements', put: 'elements', pull: 'elements'}"
            @end="$emit('end-element-drag')"
       >      
      <div class="card mb-2 mt-2" v-for="(element, index) in elements" :key="index" >

          <div class="card-body">
            <div class="clearfix mb-1" >
                <div class="float-left">
                    <span class="badge badge-light text-uppercase p-1"> <i :class="iconClass(element.type)"></i>  {{ element.type }}</span>
                </div>
                <div class="editor-row-menu float-right">
                    <b-button  @click="handleEmit('open-modal-element', index)" size="sm">
                        <i class="fa fa-edit"></i>
                    </b-button>

                    <b-button  @click="handleEmit('confirm-delete', index)" size="sm">
                        <i class="fa fa-trash-alt"></i>
                    </b-button>

                </div>
            </div>
            <render-element
                :element="element" />
          </div>
      </div>
        </draggable>
  </div>
</template>

<script>
import draggable from 'vuedraggable'
import RenderElement from './RenderElement.vue'
export default {
    components: {
        RenderElement,
        draggable
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
                case "link":
                    return "fa fa-link"
                case "list":
                    return "fa fa-th-list"
                case "table":
                    return "fa fa-table"           
                default:
                    return "fa fa-align-left"
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

    .card:hover {
        cursor:move;
        background: #ecf0f1;
        transition: ease-in-out all 0.3s;
    }

    .card-body {
        padding: 0.75rem!important;
    }

    @media (max-width: 1399.98px) { 
        .card-body, .card-header {
            padding: 7px!important;
        }

        .card-body .btn {
            font-size: 8px!important;
            padding: 0 2px 0 2px;
        }

        .card-body .badge {
            font-size: 8px;
        }

    }
</style>>

</style>
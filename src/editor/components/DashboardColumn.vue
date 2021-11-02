<template>
    <div>
    <div v-if="columns.length == 0" class="text-center lead text-muted">Row is empty</div>
        
        <draggable 
            class="row" v-else
            :list="columns"
            ghost-class="ghost-column"
            handle=".column-handle"
            :group="{name: 'columns', put: 'columns', pull: 'columns'}"
        >
        <div class="editor-column col-md" v-for="(column, index) in columns" :key="index">
            <div class="card">
             <div class="card-header column-handle">
                <div class="float-left">
                    <small class="text-muted">Column - #{{ index + 1}} </small>
                </div>
                <div class="editor-row-menu float-right">

                    <b-button @click="handleEmit('open-modal-element', index)" size="xs">
                        <i class="fa fa-plus"></i>
                    </b-button>

                    <b-button @click="handleEmit('delete-column', index)" size="xs">
                        <i class="fa fa-trash-alt"></i>
                    </b-button>                          

                </div>                
            </div>
            <div class="card-body">

                <div v-if="column.elements.length == 0" class="text-center lead">Column is empty</div>
                <div v-else>
                    <slot 
                        :c_id="index"
                        :col_length="columns.length"
                        :elements="column.elements">
                    </slot>                    
                </div>
                
            </div>                            
        </div>
    </div>
    </draggable>
    </div>

</template>

<script>
import draggable from 'vuedraggable'

export default {
    components: {
        draggable
    },
    props: [
        'columns',
        'r_id'
    ],
    methods: {
        handleEmit(event, c_id) {
            let params = {
                "c_id": c_id,
                "r_id": this.r_id,
                "e_id": null
            }
            
            this.$emit(event, params)
        }
    }
}
</script>

<style>
    .ghost-column .card {
        opacity: 0.5;
        background: #c8ebfb;
    }
    .column-handle:hover {
        cursor:move;
        background: #ecf0f1;
        transition: ease-in-out all 0.3s;
    }    
</style>
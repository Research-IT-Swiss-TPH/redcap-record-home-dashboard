<template>
    <div>
    <div v-if="columns.length == 0" class="text-center lead text-muted">Row is empty</div>
    <div class="row" v-else>
        <div class="editor-column col-md" v-for="(column, index) in columns" :key="index">
            <div class="card">
             <div class="card-header">
                <div class="float-left">
                    <small class="text-muted">Column - #{{ index + 1}} </small>
                </div>
                <div class="editor-row-menu float-right">

                    <b-button @click="handleEmit('add-element', index)" size="sm">
                        <i class="fa fa-plus"></i>
                    </b-button>

                    <b-button @click="handleEmit('delete-column', index)" size="sm">
                        <i class="fa fa-trash"></i>
                    </b-button>                          

                </div>                
            </div>
            <div class="card-body">

                <div v-if="column.elements.length == 0" class="text-center lead">Column is empty</div>
                <div v-else>
                    <slot 
                        :c_id="index"
                        :elements="column.elements">
                    </slot>                    
                </div>

            </div>                            
        </div>
    </div>
</div>
    </div>

</template>

<script>
export default {

    props: [
        'columns',
        'r_id'
    ],
    methods: {
        handleEmit(event, c_id) {
            let params = {
                "c_id": c_id,
                "r_id": this.r_id
            }
            
            this.$emit(event, params)
        }
    }
}
</script>

<style>

</style>
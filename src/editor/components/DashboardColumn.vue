<template>
    <div>
    <div v-if="columns.length == 0" class="text-center text-monospace">Row is empty.</div>
    <div class="row" v-else>
        <div class="editor-column col-md" v-for="(column, index) in columns" :key="index">
            <div class="card">
             <div class="card-header">
                <div class="float-left">
                    <span class="font-weight-light text-secondary "># {{ index }}</span>
                </div>
                <div class="editor-row-menu float-right">

                     <o-button @click="handleEmit('add-element')" size="small" variant="success">
                        <o-icon icon="plus" ></o-icon>
                    </o-button>                    
    
                     <o-button @click="handleEmit('delete-column', index)" size="small" variant="danger">
                        <o-icon icon="trash"></o-icon>
                    </o-button>

                </div>                
            </div>
            <div class="card-body">
                <div v-if="column.content.length == 0" class="text-center text-monospace">Column is empty.</div>
                <div v-else>
                    <div class="text-center">
                        Show widgets here.
                    </div>
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
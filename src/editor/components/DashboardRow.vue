<template>
        <div 
            class="card editor-row">
            <div class="card-header row-header row-handle">
                <div class="float-left">
                    <small class="text-muted">Row - #{{ r_id + 1}} </small>                
                </div>                
                <div class="editor-row-menu float-right">

                    <small class="text-muted text-monospace mr-1" v-if="isDisabled">(max. columns)</small>

                    <b-button :disabled="isDisabled" @click="handleEmit('add-column')" size="sm">
                        <i v-if="!isDisabled" class="fa fa-plus"></i>
                        <i v-else class="fa fa-ban"></i>
                    </b-button>

                    <b-button @click="handleEmit('confirm-delete')" size="sm">
                        <i class="fa fa-trash-alt"></i>
                    </b-button>                

                </div>

            </div>            
            <div class="card-body">
                <slot></slot>
            </div>
            <b-modal 
                centered
                @ok="handleEmit('delete-row')"
                size="sm"
                id="modal-confirm-delete" title="Confirm Deletion">
                <template #modal-footer="{ ok, cancel}">
                    <b-button @click="cancel()">
                        Cancel
                    </b-button>                         
                    <b-button variant="danger" @click="ok()">
                        Delete
                    </b-button>                            
                </template>
                <p class="my-4">Are you sure to delete this element?</p>
            </b-modal>
        </div>        
</template>

<script>
export default {
    data(){
        return {
            max_col_per_row: 6
        }
    },
    props: [
        'r_id',
        'col_num'
    ],
    methods: {
        handleEmit(event) {
            this.$emit(event, this.r_id)        
        }
    },
    computed: {
        isDisabled() {
            return this.col_num >= this.max_col_per_row
        },
        deletion() {

        }
    }

}
</script>

<style scoped>
    .editor-row {
        margin: 25px;
    }

    @media (max-width: 1399.98px) { 
        .editor-row{
            margin: 7px;
        }

        .card-body {
            padding: 7px;
        }
    }

    .card-body {
        min-height: 75px;
    }

    .row-handle:hover {
        cursor:move;
        background: #ecf0f1;
        transition: ease-in-out all 0.3s;
    }
    

</style>
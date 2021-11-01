<template>
        <div 
            class="card editor-row">
            <div class="card-header row-header row-handle">
                <div class="float-left">
                    <small class="text-muted">Row - #{{ r_id + 1}} </small>                
                </div>                
                <div class="editor-row-menu float-right">

                    <b-button @click="handleEmit('add-column')" size="xs">
                        <i class="fa fa-plus"></i>
                    </b-button>

                    <b-button  @click="handleEmit('delete-row')" size="xs">
                        <i class="fa fa-trash-alt"></i>
                    </b-button>

                </div>

            </div>            
            <div class="card-body">
                <slot></slot>
            </div>
        </div>        
</template>

<script>
export default {
    props: [
        'r_id'
    ],
    methods: {
        handleEmit(event) {
            switch (event) {
                case 'delete-row':
                    this.$emit('delete-row', this.r_id)
                    break
                case 'add-column':
                    this.$emit('add-column', this.r_id)
                    break
                default:
                    break
            }
        }
    }

}
</script>

<style scoped>
    .editor-row {
        margin: 25px;
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
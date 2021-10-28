<template>
        <div class="card editor-row">
            <div class="card-header row-header">
                <div class="float-left">
                    <span class="font-weight-light text-secondary "># {{ r_id }}</span>
                </div>
                
                <div class="editor-row-menu float-right">

                     <o-button @click="handleEmit('add-column')" size="small" variant="success">
                        <o-icon icon="plus" ></o-icon>
                    </o-button>        

                     <o-button @click="handleEmit('delete-row')" size="small" variant="danger">
                        <o-icon icon="trash"></o-icon>
                    </o-button>

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

<style>
    .editor-content-row {
        border: 1px dashed;
        border-color: var(--oruga-variant-gray);
        border-radius: 4px;
        margin: 15px;
        padding: 30px;
    }

    .editor-row {
        margin: 25px;;
    }

    .row-header:hover {
        cursor:grab;
    }

    .card-body {
        min-height: 75px;
    }

</style>
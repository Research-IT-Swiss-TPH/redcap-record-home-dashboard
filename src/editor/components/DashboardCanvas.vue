<template>
    <div>
        <b-overlay variant="light"
            blur="50px"
            spinner-variant="dark"
            spinner-small
            opacity="0.95"
            :show="isOverlayed" 
            rounded="sm">
            <template #overlay>
                <loader />
            </template>
            
            <div class="editor-wrapper mt-3" 
                :class="rows.length == 0 && 'empty'">

                <div v-if="rows.length == 0" id="content-empty" @click="rowAdd()">
                    <div v-if="!isOverlayed" class="text-center " id="add-row-text">
                        <p class="text-muted lead">Dashboard is empty</p>
                        <p class="h2 text-secondary"><i class="fas fa-plus-square"></i> Add row</p>
                    </div>
                </div>
                <div v-if="rows.length != 0">

                    <draggable ghost-class="ghost"
                        :list="rows"
                        handle=".row-handle"
                        :group="{name: 'rows'}"
                        @end="handleReorderElement('Row')"
                        >
                        <dashboard-row v-for="(row, index) in rows" 
                            :key="index" 
                            :r_id="index"                 
                            @delete-row="rowRemove( $event )" 
                            @add-column="columnAdd( $event )"
                            >
                            <dashboard-column :columns="row.columns"
                                :r_id="index"
                                @add-element="elementAdd( $event )"
                                @delete-column="columnRemove( $event )"
                                @open-modal-element="handleModalElement( $event )"
                                @end-col-drag="handleReorderElement('Column')"
                                v-slot="{elements, c_id}">
                                    
                                <dashboard-element
                                    :r_id="index"
                                    :c_id="c_id"
                                    :elements="elements"
                                    @delete-element="elementRemove( $event )"
                                    @open-modal-element="handleModalElement( $event )"
                                    @end-element-drag="handleReorderElement('Element')"
                                />                        

                            </dashboard-column>
                        </dashboard-row>
                    </draggable>

                    <div @click="rowAdd()" class="add-row-area">
                        <div class="text-center">
                            <span class="lead"><i class="fas fa-plus-square"></i> Add row</span>
                        </div>
                    </div>
                </div>
            </div>
        </b-overlay>

        <modal-element
            :rows="rows"
            :selection="selection"
            @add-element="elementAdd( $event )"
            @edit-element="elementEdit( $event )" 
        />

    </div>
</template>

<script>
import DashboardRow from './DashboardRow.vue';
import DashboardColumn from './DashboardColumn.vue'
import DashboardElement from './DashboardElement.vue'
import ModalElement from './ModalElement.vue'
import draggable from 'vuedraggable'
import loader from '../../loader.vue'

export default {
    components: {
        DashboardRow,
        DashboardColumn,
        DashboardElement,
        ModalElement,
        draggable,
        loader
    },
    data() {
        return {
            rows: [],
            page: getPage(),
            selection: null,
            isOverlayed: true
        }
    },
    methods: {
        rowAdd() {
            let row = { "columns": []}
            this.rows.push(row)
            this.saveDashboardData('Row added')
        },
        rowRemove(r_id) {
            this.rows.splice(r_id,1)
            this.saveDashboardData('Row removed')
        },
        columnAdd(r_id) {
            let columns = this.rows[r_id].columns
            let col = { "elements": [] }
            columns.push(col)
            this.saveDashboardData('Column added')
        },
        columnRemove(e) {
            let columns = this.rows[e.r_id].columns
            columns.splice(e.c_id, 1)
            this.saveDashboardData('Column removed')
        },
        elementAdd(e) {
            let column = this.rows[e.r_id].columns[e.c_id]
            column.elements.push(e.el)
            this.isOverlayed = true
            this.saveDashboardData('Element added')
        },
        elementEdit(e) {
            let element = this.rows[e.r_id].columns[e.c_id].elements[e.e_id]
            element.content = e.el.content
            element.type = e.el.type
            this.saveDashboardData('Element edited')
        },
        elementRemove(e) {
            let elements = this.rows[e.r_id].columns[e.c_id].elements
            elements.splice(e.e_id,1)
            this.saveDashboardData('Element removed')
        },
        handleModalElement(selection) {
            this.selection = selection
            this.$bvModal.show('modal-element')
        },
        handleReorderElement(el) {
            this.saveDashboardData( el + ' order changed' )
        },
        async loadDashboardData() {

          this.axios({
            params: {
              action: 'get-dashboard-data'
            }
          })
          .then( response => {
            let json = response.data;
            setTimeout(()=> {
                this.rows = JSON.parse(json)
                this.toast("Successfully loaded", "Dashboard Data", 'success')
            }, 500)              
          })
          .catch(e => {
            this.toastError(e)
          })
          .finally(()=> {
            setTimeout(()=> {
                this.isOverlayed = false
            }, 500)              
          })

        },
        async saveDashboardData(msg) {
            this.isOverlayed = true
            this.axios({
                params: {
                action: 'save-dashboard-data',
                new: JSON.stringify(this.rows)
                }
            })
            .then( response => {
                this.toast(msg, "Changes saved", 'success')
            })
            .catch(e => {
                this.toast(e, "Error", 'danger')
            })
            .finally(()=> {
                setTimeout(() => {
                    this.isOverlayed = false
                }, 500)
            })

        },                
    },
    mounted() {
        this.loadDashboardData()
    }
}
</script>

<style>
    .editor-wrapper {
        min-height: 800px;
        width:100%;
        border:1px solid #dbdbdb;
        border-radius: 4px;
        border-style: dashed;
        position: relative;
        transition: all ease-in-out 250ms;
        background: #f8f9fa;
    }

    .editor-wrapper.empty {
        background: none;
    }

    .editor-wrapper.empty:hover{
        cursor: pointer;
        background: #f8f9fa;
    }

    #content-empty {
        position: absolute;
        height: 100%;
        width:100%;
    }    

    #add-row-text {
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 18px;
        letter-spacing: 1px;
        transition: ease-in-out all 0.3s;
    }

    .editor-wrapper.empty:hover #add-row-text {
        color: #343a40;
    }

    .add-row-area {
        border: 1px dashed #dbdbdb;
        border-radius: 4px;
        color: #dbdbdb;
        margin: 25px;
        padding: 45px;
        cursor: pointer;
        transition: ease-in-out all 0.2s;
    }

    .add-row-area:hover {
        border-color: #343a40;
        color: #343a40;
    }

    .ghost {
        opacity: 0.5;
        background: #c8ebfb;
    }

</style>
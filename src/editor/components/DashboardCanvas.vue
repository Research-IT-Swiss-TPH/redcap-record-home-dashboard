<template>
    <div>
        <div class="text-right">            
            <b-dropdown :disabled="isOverlayed" right  text="Manage" variant="outline-secondary">
                <b-dropdown-item v-b-modal.import-modal><i class="fa fa-file-upload"></i> Import</b-dropdown-item>
                <b-dropdown-item :disabled="rows.length==0" v-b-modal.export-modal><i class="fa fa-file-download"></i> Export</b-dropdown-item>
                <b-dropdown-divider></b-dropdown-divider>
                <b-dropdown-item :disabled="rows.length==0" v-b-modal.reset-modal><i class="fa fa-eraser"></i> Reset</b-dropdown-item>
            </b-dropdown>
        </div>

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
                            :col_num="row.columns.length"
                            @confirm-delete="confirmDelete( $event, 'row' )"
                            @add-column="columnAdd( $event )"
                            >
                            <dashboard-column :columns="row.columns"
                                :r_id="index"
                                @add-element="elementAdd( $event )"
                                @confirm-delete="confirmDelete( $event, 'column' )"
                                @open-modal-element="handleModalElement( $event )"
                                @end-col-drag="handleReorderElement('Column')"
                                v-slot="{elements, c_id}">
                                    
                                <dashboard-element
                                    :r_id="index"
                                    :c_id="c_id"
                                    :elements="elements"
                                    @confirm-delete="confirmDelete( $event, 'element' )"                                    
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

        <!-- Confirm Delete modal -->
        <b-modal
            centered
            :title="'Delete '+deletion.ctx"
            id="confirm-delete-modal"
            size="sm">
            <p class="my-4">Are you sure to delete this element?</p>            
            <template #modal-footer="{cancel}">
                <b-button @click="cancel()">
                    Cancel
                </b-button>                         
                <b-button variant="danger" @click="handleDelete(deletion)">
                    Delete
                </b-button>                            
            </template>
        </b-modal>

        <!-- Element modal component -->
        <modal-element
            :rows="JSON.parse(JSON.stringify(rows))"
            :selection="selection"
            @add-element="elementAdd( $event )"
            @edit-element="elementEdit( $event )" 
        />

        <!-- Export modal -->
        <b-modal
            @ok="handleExport()"
            ok-title-html="<i class='fa fa-file-export'></i> Export"
            centered scrollable
            id="export-modal" 
            title="Export Dashboard">            
            Click Download to export current Dashboard as .json file. You can use this file to import it in any Record Home Dashboard.
        </b-modal>

        <!-- Import modal -->
        <b-modal            
            @ok="handleImport"
            @hidden="resetImport"
            centered scrollable
            id="import-modal" 
            title="Import Dashboard">            
            Select a valid file to be imported. <br>The import overwrites the current data and <b>cannot be undone</b>.
            <b-form-file                
                class="mt-2"
                accept=".json"
                v-model="importFile"
                @input="validateImportFile()"
                :state="!error"
                required
                invalid-feedback="File is invalid"
                placeholder="Choose a file or drop it here..."
                drop-placeholder="Drop file here..."
            ></b-form-file>

            <b-alert class="mt-3" v-if="!error && importFile != null" variant="success" show><b>Valid file:</b> {{ importFile ? importFile.name : '' }}</b-alert>
            <b-alert class="mt-3" v-if="error && importFile != null" variant="danger" show><b>File is invalid.</b> Please verify that your json file is correctly formatted.</b-alert>

            <template #modal-footer="{ ok, cancel }">
                <b-button @click="cancel()">
                    Cancel
                </b-button>
                <b-button :disabled="error || importFile==null" variant="primary" @click="ok()">
                    <i class='fa fa-file-export'></i> Import
                </b-button>            
            </template>
        </b-modal>

        <!-- Reset Modal -->
        <b-modal
            @ok="handleReset"            
            centered scrollable
            id="reset-modal" 
            title="Reset Dashboard"
        >
        Are you sure you want to delete all data and reset the Dashboard? <br>This <b>cannot be undone.</b>
        <template #modal-footer="{ ok, cancel }">
            <b-button @click="cancel()">
                Cancel
            </b-button>
            <b-button variant="danger" @click="ok()">
                <i class='fa fa-exclamation-circle'></i> Delete data
            </b-button>            
        </template>        
        </b-modal>

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
            selection: null,
            isOverlayed: true,
            importFile: null,
            importJSON: "",
            error: false,
            deletion: {
                target: "",
                ctx: ""
            }
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
        confirmDelete(target, ctx) {
            this.deletion.target = target
            this.deletion.ctx = ctx
            this.$bvModal.show('confirm-delete-modal')
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
                if(this.rows == null) {
                    this.rows = []
                }
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

        handleDelete(deletion){
            if(deletion.ctx == 'row') {
                this.rowRemove(deletion.target)
            } 
            else if(deletion.ctx == 'column') {
                this.columnRemove(deletion.target)
            }
            else if(deletion.ctx == 'element') {
                this.elementRemove(deletion.target)
            }
            
            this.$bvModal.hide('confirm-delete-modal')
        },

        handleExport() {
            const blob = new Blob([JSON.stringify(this.rows)], { type: 'application/json' })
            const link = document.createElement('a')
            link.href = URL.createObjectURL(blob)
            link.download = "record_home_dashboard_"+(new Date()).getTime()+".json"
            link.click()
            URL.revokeObjectURL(link.href)
        },

        handleImport() {        
            this.rows = this.importJSON
            this.saveDashboardData("Dashboard imported.")
            console.log("Dashboard imported.")
        },

        handleReset() {
            this.rows = []
            this.saveDashboardData("Dashboard reset.")
            console.log("Dashboard reset.")
        },

        resetImport() {
            this.error = false
            this.importFile = null
            this.importJSON = ""
        },

        async validateImportFile() {
            this.error = false
            let read = new FileReader()
            read.readAsBinaryString(this.importFile)
            read.onloadend = () => {

                let json = JSON.parse(read.result)                

                for( var i = 0; i < json.length; i++) {                    
                    //  check if has columns
                    if( !('columns' in json[i]) ) {                        
                        this.error = true
                        break
                    } 
                    let columns = json[i].columns
                    //  check if has elements
                    for(var j = 0; j < columns.length; j++) {                        
                        if(!('elements' in columns[j])) {
                            this.error = true
                            break
                        }

                        let elements = columns[j].elements
                        const allowedElTypes = ["text", "link", "list", "table"]
                        //  Only if elements contain any element
                        if(elements.length > 0) {
                        
                            for(var k=0; k < elements.length; k++) {
                                //  Check if element types are allowed
                                //  we could also check for content structure here but let's leave it for another time..
                                let elType = elements[k].type
                                if( !allowedElTypes.includes(elType) ) {
                                    this.error = true
                                    break
                                }
                            }
                        }
                    }                    
                }

                this.importJSON = json
            }
        }        
    },
    mounted() {
        this.loadDashboardData()

        this.$root.$on('bv::modal::hidden', (bvEvent, modalId) => {
            //  Cleanup selection from parent (otherwise Vue warns of props modification from child component)
            this.selection = null
        })
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
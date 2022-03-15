<template>
    <div>
        <b-modal 
        @ok="handleOk()"
        centered scrollable
        title="Create/Edit Dashboard Element"
        id="modal-element">
            <div class="text-center">
                <b-skeleton-wrapper :loading="isLoading">
                    <template #loading>
                        <div class="text-center">
                            <b-skeleton height="50px" width="400px"></b-skeleton>
                        </div>
                    </template>
                    <b-form-group>
                        <b-form-radio-group
                            id="btn-radios-2"
                            v-model="selected.type"
                            :options="options.type"
                            button-variant="outline-secondary"
                            size="lg"
                            name="radio-btn-outline"
                            buttons>
                        </b-form-radio-group>
                    </b-form-group>
                </b-skeleton-wrapper>                
            </div>

            <div>
                <b-skeleton-wrapper :loading="isLoading">
                    <template #loading>
                        <div class="text-center">
                            <b-skeleton height="300px" width="400px"></b-skeleton>
                        </div>
                    </template>
                    <div style="min-height:300px;" class="p-3">
                        <b-alert v-if="hasTypeChange" variant="warning" show><b>Warning:</b> Saving as new type removes all data from type <b class="text-capitalize">{{ element.type}}</b>.</b-alert>
                        
                        <div v-if="selected.type=='text'">
                            <b-form-group                               
                                class="text-right font-weight-bold"
                                label="Title"
                                label-cols-lg="3"
                                content-cols-lg="9">
                                <b-form-input v-model="content.text.title"></b-form-input>
                            </b-form-group>
                            <b-form-group                               
                                class="text-right font-weight-bold"
                                label="Decoration"
                                label-cols-lg="3"
                                content-cols-lg="9">
                                <b-form-checkbox-group                                    
                                    v-model="content.text.decoration"
                                    :options="options.decoration"
                                    button-variant="outline-info"                                    
                                    name="text-decoration"
                                    buttons
                                ></b-form-checkbox-group>
                            </b-form-group>                            
                        </div>

                        <div v-if="selected.type=='link'">
                            <b-form-group
                                class="text-right font-weight-bold"
                                label="Title"
                                label-cols-lg="3"
                                content-cols-lg="9">
                                <b-form-input v-model="content.link.title"></b-form-input>
                                </b-form-group>

                            <b-form-group
                                class="text-right font-weight-bold"                            
                                label="URL"
                                label-cols-lg="3"
                                content-cols-lg="9">
                                <b-form-input
                                class="text-monospace"
                                v-model="content.link.url">
                                </b-form-input>
                            </b-form-group>

                            <b-form-group
                                class="text-right font-weight-bold"                            
                                label="Icon"
                                label-cols-lg="3"
                                content-cols-lg="9">
                                <b-input-group>
                                    
                                    <b-form-input
                                    class="text-monospace"
                                    v-model="content.link.icon">
                                    </b-form-input>

                                    <b-input-group-append>
                                        <b-button 
                                            variant="info"                                            
                                            style="display:flex;align-items: center;" 
                                            target="_blank" 
                                            href="https://fontawesome.com/v5/cheatsheet">
                                            <i class="fa fa-search"></i>
                                        </b-button>
                                    </b-input-group-append>

                                </b-input-group>
                            </b-form-group>

                            <b-form-group
                                class="text-right font-weight-bold"                            
                                label="Variant"
                                label-cols-lg="3"
                                content-cols-lg="9">
                                <b-form-select v-model="content.link.variant">
                                    <b-form-select-option class="text-info" value="info">Info</b-form-select-option>
                                    <b-form-select-option class="text-warning" value="warning">Warning</b-form-select-option>
                                    <b-form-select-option class="text-success" value="success">Success</b-form-select-option>
                                    <b-form-select-option class="text-danger" value="danger">Danger</b-form-select-option>
                                    <b-form-select-option class="text-secondary" value="secondary">Secondary</b-form-select-option>
                                </b-form-select>
                            </b-form-group>                            

                            <b-form-group
                                class="text-right font-weight-bold"                            
                                label="Target"
                                label-cols-lg="3"
                                content-cols-lg="9">
                                <b-form-select v-model="content.link.target">
                                    <b-form-select-option value="_self">Same window</b-form-select-option>
                                    <b-form-select-option value="_blank">New window</b-form-select-option>
                                </b-form-select>
                            </b-form-group>                            

                        </div>

                        <div v-if="selected.type=='list'">                            
                            <draggable tag="ul" :list="content.list" class="list-group" handle=".list-element-handle">
                                <li
                                class="list-group-item"
                                v-for="(element, idx) in content.list"
                                :key="idx"
                                >
                                <i class="fa fa-align-justify list-element-handle mr-2"></i>
                                <input placeholder="title" class="ml-1 list-element-input" type="text"  v-model="element.title" />
                                <input placeholder="value" class="ml-1 list-element-input" type="text"  v-model="element.value" />
                                <i class="fa fa-times close list-element-delete" @click="removeListElement(idx)"></i>
                                </li>
                            </draggable>
                            <div class="mt-2 pb-4 float-right">
                                <b-button size="sm" @click="addListElement"><i class="fa fa-plus"></i> Add</b-button>
                            </div>
                        </div>

                        <div v-if="selected.type=='table'">
                            <b-form-group                               
                                class="text-right font-weight-bold"
                                label="Instrument (Repeating)"
                                label-cols-lg="3"
                                content-cols-lg="9">                                
                                <b-form-select 
                                v-model="content.table.instrument"
                                @change="changeInstrument()"
                                :options="getRepeatingInstruments()"></b-form-select>
                            </b-form-group>

                            <div  v-if="content.table.instrument && fields" >

                                <b-form-group                               
                                class="text-right font-weight-bold"
                                label="Columns"
                                label-cols-lg="3"
                                content-cols-lg="9">                                

                                <div v-for="field,idx in fields" :key="idx">
                                    <b-form-checkbox :disabled="columns.length > 9 && selected.fields[idx] != true"  class="text-left font-weight-light" v-model="selected.fields[idx]"  name="check-button" switch>
                                        {{ field }}
                                    </b-form-checkbox>
                                </div>                              
                                </b-form-group>

                               <div v-if="columns && columns.length > 0">
                                   <b-alert variant="success" show><b>Selected Columns:</b> {{ columns.length }}</b-alert>
                                 
                               </div>
                               <div v-else>
                                   <b-alert show><b>No columns selected.</b> <br>All columns (max. 10) will be rendered.</b-alert>
                               </div>

                            </div>
                        
                        
                                                                
                        </div>


                    </div>
                </b-skeleton-wrapper>
            </div>

            <template #modal-footer="{ ok, cancel }">
                <b-skeleton v-if="isLoading" type="button"></b-skeleton>
                <b-skeleton v-if="isLoading" type="button"></b-skeleton>
                <b-button v-if="!isLoading" variant="secondary" @click="cancel()">
                    Cancel
                </b-button>
                <b-button v-if="!isLoading" variant="info" @click="ok()">
                    Save
                </b-button>
            </template>

        </b-modal>
    </div>
</template>

<script>

class ModalContent {
      constructor() {
         this.text = { title: "", decoration: [] }
         this.link = { title: "", url: "", variant:"info", target: "_self" }
         this.list = [{title: "", value: ""}]
         this.table = { instrument: "", columns: [] }
      }
      getObj() {
          return this
      }
}

import draggable from 'vuedraggable'

export default {
    components: {
        draggable
    },
    props: [
        'selection',
        'rows'
    ],
    data() {
        return {
            isLoading: true,
            content: (new ModalContent).getObj(),
            selected: {
                type: "text",
                instrument: "",
                fields: []
            },
            options: {
                type: [
                        { html: '<i class="fas fa-align-left"></i> Text', value: 'text' },
                        { html: '<i class="fas fa-link"></i> Link', value: 'link' },
                        { html: '<i class="fas fa-th-list"></i> List', value: 'list' },
                        { html: '<i class="fas fa-table"></i> Table', value: 'table' }
                    ],
                decoration: [
                    { html: '<i class="fas fa-bold"><i/>', value: 'bold' },
                    { html: '<i class="fas fa-italic"><i/>', value: 'italic' },
                    { html: '<i class="fas fa-underline"><i/>', value: 'underline' }
                ]
            },
            fields: []
            
        }
    },
    computed: {
        element: function() {
            if(this.selection) {
                return this.rows[this.selection.r_id].columns[this.selection.c_id].elements[this.selection.e_id]
            } 
        },
        hasTypeChange: function() {
            if( this.element && this.element.type != this.selected.type) {
                return true
            }
            return false
        },

        columns: function() {
            return this.selected.fields.map( (field, idx) => {
                if(field == true) {
                    return this.fields[idx]
                }
            }).filter( (name) => {
                return typeof name !== 'undefined'
            })
        },

    },
    methods: {
        removeListElement(idx) {
            if(this.content.list.length > 1) {
                this.content.list.splice(idx, 1)
            }            
        },
        addListElement() {
            let lEl = {
                title: "",
                value: ""
            }
            this.content.list.push(lEl)
        },
        prefill: function() {
            this.selected.type = this.element.type            
            this.content[this.element.type] = this.element.content
            if(this.selected.type == 'table' && this.content["table"].instrument.length > 0) {
                this.getFieldsForInstrument()                
            }
        },
        changeInstrument() {
            this.selected.fields = []
            this.getFieldsForInstrument()
        },
        getRepeatingInstruments: function() {
            return stph_rhd_getRepeatingInstruments()
        },
        async getFieldsForInstrument() {            
            this.axios({
            params: {
                action: "get-field-for-instrument",
                instrument: this.content.table.instrument
                }
            })
            .then( response => {
                this.fields = response.data
                this.setInitialFields()                
            })
            .catch(error => {
                console.log(error)
            })
        },

        setInitialFields() {
            this.fields.forEach( (element,idx) => {
                if(this.content.table.columns.includes(element)) {
                    //this.selected.fields[idx] = true
                    //  Use set to overcome #Change-Detection-Caveats
                    this.$set(this.selected.fields, idx, true)
                }                
            });
        },

        handleOk() {

            //  Cleanup list content before handle: only include list elements where value is set
            if(this.selected.type == "list") {
                let filtered = this.content["list"].filter( (li) => {
                    return li.value != ""
                })
                this.content["list"] = filtered
            }

            let el = {
                "type": this.selected.type,
                "content": this.content[this.selected.type]
            }
            if(this.selection.e_id == null) {

                this.$emit('add-element', {
                    "c_id": this.selection.c_id,
                    "r_id": this.selection.r_id,
                    "el": el
                })

            } else {
                this.$emit('edit-element', {
                    "c_id": this.selection.c_id,
                    "r_id": this.selection.r_id,
                    "e_id": this.selection.e_id,
                    "el": el
                })
            }
        }        
    },
    mounted() {

        /*  Modal Events - 
         *  https://bootstrap-vue.org/docs/components/modal#comp-ref-b-modal-events
         *  
         */

        //   shown
        this.$root.$on('bv::modal::shown', (bvEvent, modalId) => {

            if(this.element) {
                this.prefill()

                setTimeout(() => {
                    this.isLoading = false
                }, 500)
            } else {
                setTimeout(() => {
                    this.isLoading = false
                }, 250)
            }

        })

        //  Modal Event: hidden
        this.$root.$on('bv::modal::hidden', (bvEvent, modalId) => {
            this.isLoading = true
            this.content = (new ModalContent).getObj()
            this.selected.instrument = ""
            this.selected.fields = []
        })

    },

    watch: {
        columns: function (val) {
            //  Do not watch when setting initial values
            if( val.length !== 0 ) {
                this.content.table.columns = val
            } else {
                this.content.table.columns = []
            }
        }
    }

}
</script>

<style scoped>
.modal-content {
    height: 400px;
    max-height: 400px;
}
 .b-skeleton {
     display: inline-block;
 }
 .list-element-input {
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    padding: 3px;
    line-height: 1.5;
    color: #495057;    
 }

 .list-element-delete {
    position: absolute;
    margin-left: 7px;
    font-size: 20px;
    margin-top: 4px;
 }

 .list-element-handle:hover {
     cursor: move;
 }

 .list-element-delete:hover {
     cursor: pointer;
 }

</style>
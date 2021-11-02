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
                                label="Description"
                                label-cols-lg="3"
                                content-cols-lg="9">
                                <b-form-textarea       
                                rows="5"
                                max-rows="5"
                                v-model="content.text.description"
                                >
                                </b-form-textarea>
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
                                v-model="content.link.url">
                                </b-form-input>
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
         this.text = { title: "", description: "" }
         this.link = { title: "", url: "" }
         this.list = [{title: "", value: ""}]
         this.table = { instrument: ""}
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
                type: "text"
            },
            options: {
                type: [
                        { html: '<i class="fas fa-align-left"></i> Text', value: 'text' },
                        { html: '<i class="fas fa-link"></i> Link', value: 'link' },
                        { html: '<i class="fas fa-th-list"></i> List', value: 'list' },
                        { html: '<i class="fas fa-table"></i> Table', value: 'table' }
                    ]
            },
        }
    },
    computed: {
        element: function() {
            if(this.selection) {
                return this.rows[this.selection.r_id].columns[this.selection.c_id].elements[this.selection.e_id]
            }
        },
        preType: function() {
            return this.element.type
        },
        preContent: function() {
            return this.element.content
        }
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
            this.selected.type = this.preType
            this.content[this.preType] = this.preContent
        },
        handleOk() {

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
        })
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

 .list-element-handle:hover {
     cursor: move;
 }

 .list-element-delete:hover {
     cursor: pointer;
 }
</style>
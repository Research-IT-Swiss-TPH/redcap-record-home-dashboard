<template>
    <div class="editor-wrapper mt-3">

        <div v-if="rows.length == 0" id="content-empty" @click="rowAdd()">
            <div class="text-center" id="add-row-text">
                <span class="text-monospace">Dashboard is empty.</span><br>
                <a class="text-decoration-none" href="#add-row-to-empty"><i class="fas fa-plus-square"></i> Add new row</a>
            </div>
        </div>

        <div v-if="rows.length != 0">

            <dashboard-row 
                v-for="(row, index) in rows" 
                :key="index" 
                :r_id="index" 
                @delete-row="rowRemove( $event )" 
                @add-column="columnAdd( $event )">

                <dashboard-column 
                    :columns="row.columns"
                    :r_id="index"
                    @delete-column="columnRemove( $event )">
                </dashboard-column>

            </dashboard-row>
        </div>

        <div class="text-center mt-3" v-if="rows.length != 0">
            <a @click="rowAdd()" class="text-decoration-none text-is-secondary" href="#add-row-to-empty"><i class="fas fa-plus-square"></i> Add row</a>
        </div>

    </div>
</template>

<script>
import DashboardRow from './DashboardRow.vue';
import DashboardRowClass from "../../classes/DashboardRowClass";
import DashboardColumn from './DashboardColumn.vue'

export default {
    components: {
        DashboardRow,
        DashboardColumn
    },
    data() {
        return {
            rows: [],
            msg: "Hello World from ",
            page: getPage()
        }
    },
    methods: {
        rowAdd() {
            let row = new DashboardRowClass()
            this.rows.push(row)
        },
        rowRemove(r_id) {
            this.rows.splice(r_id,1)
        },
        columnAdd(r_id) {
            let columns = this.rows[r_id].columns
            //let col = new DashboardColClass()
            let col = {
                "content": []
            }
            columns.push(col)
        },
        columnRemove(params) {
            let r_id = params.r_id
            let c_id = params.c_id
            let columns = this.rows[r_id].columns
            columns.splice(c_id, 1)
        },
        elementAdd(params) {
            let column = this.rows[params.r_id].columns[params.c_id]
            let el =  {
                        "type": "text",
                        "content": "Hello World"
                    }
            column.content.push(el)

        }
    },
    computed: {
    }
}
</script>

<style>
    .editor-wrapper {
            min-height: 500px;
            width:100%;
            border:1px solid #dbdbdb;
            border-radius: 4px;
            border-style: dashed;
            position: relative;
            transition: all ease-in-out 250ms;
            background: var(--oruga-variant-light);
        }

    #content-add-new {
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
        color: var(--oruga-variant-gray);
        font-size: 18px;
        letter-spacing: 1px;
    }    
</style>
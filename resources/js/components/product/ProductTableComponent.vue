<template>
    <div>
        <v-client-table  :data="tableData" :columns="columns" :options="options">
              <template v-slot:role="{ row }">
                <span>{{ formatRoles(row) }}</span>
              </template>
              <template v-slot:actions="{ row }">
                <button class="button edit-btn"  v-on:click="edit(row)"> <i class="fas fa-edit text-primary text-light"></i> Edit</button>
                <button class="button delete-btn" v-on:click="destroy(row)"> <i class="fas fa-trash text-light"></i> Delete</button>
              </template>
        </v-client-table>
    </div>
</template>

<script>
    export default {
        props : {
            tableData :{
                type : Array,
                default : [],
            },
        },  
        data(){
            return {
                columns: ['id', 'name', 'unit_price','description','brand','product_category', 'flavor','actions'],
                options: {
                    pagination : {
                    virtual : true,
                    },
                    filterable:  false, //filter change when needed
                },
            }
        },
        methods : {
            edit(row){
                this.$emit('edit', row);
            },
            destroy(row){
                this.$emit('destroy', row.id);
            }
        },
        created() {

        }
    }
</script>

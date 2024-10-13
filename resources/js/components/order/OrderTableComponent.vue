<template>
    <div>
        <v-client-table  :data="tableData" :columns="columns" :options="options">
              <template v-slot:role="{ row }">
                <span>{{ formatRoles(row) }}</span>
              </template>
              <template v-slot:actions="{ row }">
                <button class="btn" v-on:click="edit(row)"><i class="fas fa-edit text-primary"></i></button>
                <button class="btn" v-on:click="destroy(row)"><i class="fas fa-trash text-danger"></i></button>
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
                columns: ['id','order_date','tracking_number','payment_method','payment_status','delivery_status','actions'],
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

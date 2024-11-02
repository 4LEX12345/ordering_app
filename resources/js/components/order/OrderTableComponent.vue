<template>
    <div>
        <v-client-table  :data="tableData" :columns="columns" :options="options">
              <template v-slot:role="{ row }">
                <span>{{ formatRoles(row) }}</span>
              </template>
              <template v-slot:actions="{ row }">
                <button class="button view-btn" v-on:click="edit(row)"> <i class="fas fa-eye text-light"></i> View </button>
                <button class="button delete-btn" v-on:click="destroy(row)"><i class="fas fa-trash text-light"></i> Cancel</button>
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
                columns: [
                'id',
                'order_date',
                'tracking_number',
                'business_name',
                'contact_person',
                'contact_person_contact_number', 
                'contact_person_email',
                'billing_address',
                'shipping_address',
                'user_name',
                'actions'],
                options: {
                    headings : {
                        'user_name' : 'Created By',
                    },
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

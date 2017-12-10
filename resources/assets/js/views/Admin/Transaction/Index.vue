<template>
   <div class="container">
        <h1 class="h1 text-center" style="margin-bottom:54px;">
            Transactions
        </h1>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped" v-if="!aggregated">
                        <thead>
                            <tr class="success">
                                <th class="text-center">TRANSACTION ID</th>
                                <th class="text-center">TYPE</th>
                                <th class="text-center">AMOUNT</th>
                                <th class="text-center">DATE</th>
                                <th class="text-center">SOURCE</th>
                                <th class="text-center">DESTINATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="result in results">
                                <td class="text-center" v-for="key in keys">
                                    {{result[key]}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <router-link to="/admin/transactions/create" tag="button" class="btn btn-primary" style="width:100%;margin-bottom:36px;">
                        Make a Transaction
                    </router-link>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
  </div>
</template>

<script>
    import Auth from '../../../Auth/auth';
    import Flash from '../../../helpers/flash';
    import {post} from '../../../helpers/api';

    export default {
         data() {
            return {
                form: {
                    asc_desc: "ASC",
                    orderBy: "",
                    agg: "",
                    user_id: Auth.state.user_id,
                },
                results: [],
                aggregated: false,
            };
        },
        computed: {
            keys() {
                if(this.results.length>0) {
                    return Object.keys(this.results[0]);
                }
            }
        },
        methods: {
            
        },
        mounted() {
            post('/api/admin/transactions', this.form)
                .then((res) => {
                    this.results = res.data.results;
                })
                .catch((err) => {

                });
        }
    }
</script>

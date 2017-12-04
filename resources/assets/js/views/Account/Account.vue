<template>
  <div class="container">
        <h1 class="h1 text-center">
            Accounts
        </h1>
        <div class="row">
            <form @submit.prevent class="form-inline pull-right" style="background:transparent;">
                    <div class="form-group">
                        <label>Order By:</label>
                        <select class="form-control" v-model="form.orderBy">
                            <option></option>
                            <option>account_id</option>
                            <option>balance</option>
                            <option>date_created</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="radio-inline">
                            <input type="radio" v-model="form.asc_desc" value="ASC"> Ascending
                        </label>
                        <label class="radio-inline">
                            <input type="radio" v-model="form.asc_desc" value="DESC"> Descending
                        </label>
                    </div>
                    <!-- Render aggregate when conditions are fulfilled -->
                    <div class="form-group" style="margin-left:36px;">
                        <label>Aggregate:</label>
                        <select class="form-control" v-model="form.agg">
                            <option></option>
                            <option>max(balance)</option>
                            <option>max(date_created)</option>
                            <option>min(balance)</option>
                            <option>min(date_created)</option>
                            <option>avg(balance)</option>
                            <option>sum(balance)</option>
                            <option>count(account_id)</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" @click="accountsWithCondition">GO</button>
            </form>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped" v-if="!aggregated">
                        <thead>
                            <tr class="succss">
                                <th class="text-center">ACCOUNT ID</th>
                                <th class="text-center">BALANCE</th>
                                <th class="text-center">DATE_CREATED</th>
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
                    <table class="table table-striped" v-if="aggregated">
                        <thead>
                            <tr>
                                <th>{{keys[0]}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{results[0][keys[0]]}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
  </div>
</template>

<script>
    import Auth from '../../Auth/auth';
    import Flash from '../../helpers/flash';
    import {post} from '../../helpers/api';

    export default {
        created() {

        },
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
            accountsWithCondition() {
                post('/api/accounts', this.form)
                    .then((res) => {
                        this.results = res.data.results;
                        this.aggregated = res.data.aggregated;
                    })
                    .catch((err) => {

                    });
            }
        },
        mounted() {
            post('/api/accounts', this.form)
                .then((res) => {
                    this.results = res.data.results;
                })
                .catch((err) => {

                });
        }
    }
</script>

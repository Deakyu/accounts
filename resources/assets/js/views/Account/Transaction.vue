<template>
  <div class="container">
        <h1 class="h1 text-center" style="margin-bottom:54px;">
            Transactions
        </h1>
        <div class="row" style="margin-bottom:36px;">
            <form @submit.prevent class="form-inline pull-right" style="background:transparent;">
                    <div class="form-group">
                        <label>Order By:</label>
                        <select class="form-control" v-model="form.orderBy">
                            <option></option>
                            <option>transaction_id</option>
                            <option>type</option>
                            <option>amount</option>
                            <option>date</option>
                            <option>source</option>
                            <option>destination</option>
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
                            <option value="max(t.amount)">max(amount)</option>
                            <option value="max(t.date)">max(date)</option>
                            <option value="min(t.amount)">min(amount)</option>
                            <option value="min(t.date)">min(date)</option>
                            <option value="avg(t.amount)">avg(amount)</option>
                            <option value="sum(t.amount)">sum(amount)</option>
                            <option value="count(t.transaction_id)">count(transaction_id)</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" @click="transactionsWithCondition">GO</button>
            </form>
        </div>
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
                                <th class="text-center">FROM</th>
                                <th class="text-center">TO</th>
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
                            <tr class="success">
                                <th>{{keys[0]}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{results[0][keys[0]]}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- Query Result -->
            </div>
        </div>
        <div class="row" align="center">
            <form class="form-inline" @submit.prevent 
                  style="background:transparent;">
                <h2 class="h2 text-center" style="margin-bottom:36px;">Transfer Money</h2>
                <div class="form-group">
                    <label>From</label>
                    <select class="form-control" v-model="transfer.from">
                        <option v-for="account in accounts">
                            {{account.ACCOUNT_ID}}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>To</label>
                    <input type="text" 
                           class="form-control" 
                           placeholder="Type first name..."
                           @keyup="pullAccounts"
                           v-model="transfer.first_name">
                    <select class="form-control" v-model="transfer.account_id">
                        <option v-for="account in transfer.accounts_by_first" :value="account.account_id">
                            {{account.account_id}}: {{account.first_name}} {{account.last_name}}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Type:</label>
                    <select class="form-control" v-model="transfer.type">
                        <option value="T">Transfer</option>
                        <option value="D">Deposit</option>
                        <option value="W">Withdraw</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Amount:</label>
                    <input type="number" class="form-control" v-model="transfer.amount">
                </div>
                <button class="btn btn-success" @click.prevent="makeTransaction">Make Transaction</button>
            </form>
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
                transfer: {
                    first_name: "",
                    accounts_by_first: [],
                    from: "",
                    account_id: "",
                    amount: "",
                    type: "",
                    user_id: Auth.state.user_id,
                },
                accounts: [],
                results: [],
                aggregated: false,
                typed: false,
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
            transactionsWithCondition() {
                post('/api/transactions', this.form)
                    .then((res) => {
                        this.results = res.data.results;
                        this.aggregated = res.data.aggregated;
                    })
                    .catch((err) => {

                    });
            },
            pullAccounts() {
                post('/api/accounts-by-name', this.transfer)
                    .then((res) => {
                        console.log(res.data);
                        this.transfer.accounts_by_first = res.data.accounts;
                    })
                    .catch((err) => {

                    });
            },
            makeTransaction() {
                post('/api/make-transaction', this.transfer)
                    .then((res) => {
                        Flash.setSuccess(res.data.message);
                    })
                    .catch((err) => {
                        Flash.setError(err.response.data.message);
                    });
            }
        },
        mounted() {
            post('/api/transactions', this.form)
                .then((res) => {
                    this.results = res.data.results;
                    this.accounts = res.data.accounts;
                })
                .catch((err) => {

                });
        }
    }
</script>

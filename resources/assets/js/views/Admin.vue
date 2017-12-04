<template>
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="row">
                    <form class="border" @submit.prevent>
                        
                            <h1 class="h1 text-center" style="margin: 18px 0;">
                                <strong>Administrator</strong>
                            </h1>
                            <div class="form-inline" style="text-align:center;">
                                <label>Raw Statements</label>
                                <input type="text" class="form-control" placeholder="Statement..." v-model="raw.statement">
                                <button class="btn btn-primary" @click.prevent="rawQuery">Run Raw</button>
                                <div align="center" v-if="error.rawError">
                                    <small class="error-control">{{error.rawError[0]}}</small>
                                </div>
                            </div> <!-- Raw Query -- >
                            <div class="form-group" id="table-check">
                                <div class="checkbox" align="center">
                                    <h2 class="h2 text-center" style="border-bottom: 1px solid #ccc;">Tables</h2>
                                    <div align="center">
                                        <small class="error-control" v-if="error.bad_query">{{error.bad_query[0]}}</small>
                                    </div>
                                    <label class="checkbox-inline">
                                        <input v-model="tables" type="checkbox" value="users"> User
                                    </label>
                                    <label class="checkbox-inline">
                                        <input v-model="tables" type="checkbox" value="accounts"> Account
                                    </label>
                                    <label class="checkbox-inline">
                                        <input v-model="tables" type="checkbox" value="transactions"> Transaction
                                    </label>
                                </div>
                            </div> <!-- table checks -->
                            <div class="form-group" id="table-check">
                                <div class="checkbox" align="center">
                                    <h2 class="h2 text-center" style="border-bottom: 1px solid #ccc;">Tables</h2>
                                    <div align="center">
                                        <small class="error-control" v-if="error.bad_query">{{error.bad_query[0]}}</small>
                                    </div>
                                    <label class="checkbox-inline">
                                        <input v-model="tables" type="checkbox" value="users"> User
                                    </label>
                                    <label class="checkbox-inline">
                                        <input v-model="tables" type="checkbox" value="accounts"> Account
                                    </label>
                                    <label class="checkbox-inline">
                                        <input v-model="tables" type="checkbox" value="transactions"> Transaction
                                    </label>
                                </div>
                            </div> <!-- table checks --> <!-- Weired.. -->
                            <div class="row">
                                <h2 class="h2 text-center" style="border-bottom: 1px solid #ccc; margin: 18px 9px 0;">Fields</h2>
                                <div class="col-md-4" v-if="hasUsers">
                                    <div class="form-group">
                                        <h3 class="h3 text-center">Users</h3>
                                        <div class="checkbox" align="center" v-for="field in fields.users">
                                            <label>
                                                <input type="checkbox" v-model="checkedFields.users" :value="'users.' + field"> {{field}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" v-if="hasAccounts">
                                    <div class="form-group">
                                        <h3 class="h3 text-center">Accounts</h3>
                                        <div class="checkbox" align="center" v-for="field in fields.accounts">
                                            <label>
                                                <input type="checkbox" v-model="checkedFields.accounts" :value="'accounts.' + field"> {{field}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" v-if="hasTransactions">
                                    <div class="form-group">
                                        <h3 class="h3 text-center">Transactions</h3>
                                        <div class="checkbox" align="center" v-for="field in fields.transactions">
                                            <label>
                                                <input type="checkbox" v-model="checkedFields.transactions" :value="'transactions.' + field"> {{field}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- field checks -->
                            <div class="row" v-for="join in joins">
                                <h3 class="h3 text-center" style="border-bottom: 1px solid #ccc;">Join on</h3>
                                <div class="form-group col-md-4">
                                    <select class="form-control" v-model="join.field1">
                                        <option v-for="field in fields.users" :value="'users.' + field">(Users) {{field}}</option>
                                        <option v-for="field in fields.accounts" :value="'accounts.' + field">(Accounts) {{field}}</option>
                                        <option v-for="field in fields.transactions" :value="'transactions.' + field">(Transactions) {{field}}</option>
                                    </select>
                                </div>
                                <div class="col-md-2 text-center" style="font-size:24px;">
                                    =
                                </div>
                                <div class="form-group col-md-4">
                                    <select class="form-control" v-model="join.field2">
                                        <option v-for="field in fields.users" :value="'users.' + field">(Users) {{field}}</option>
                                        <option v-for="field in fields.accounts" :value="'accounts.' + field">(Accounts) {{field}}</option>
                                        <option v-for="field in fields.transactions" :value="'transactions.' + field">(Transactions) {{field}}</option>
                                    </select>
                                </div>
                                <div class="col-md-1"><button class="btn btn-primary" @click="addJoin">+</button></div>
                                <div class="col-md-1"><button class="btn btn-danger" @click="removeJoin">x</button></div>
                                <div align="center" v-if="error.join">
                                    <small class="error-control">{{error.join[0]}}</small>
                                </div>
                            </div> <!-- Join -->
                            <div class="form-group">
                                <div class="conditions" v-for="condition in conditions">
                                    <div align="center">
                                        <label class="h3 text-center">Condition {{condition.index}}</label>
                                        <button class="btn btn-danger" style="position:absolute;right:18px;margin-top:18px;" @click="removeCondition">Remove</button>
                                    </div>
                                    <select class="form-control" v-model="condition.field">
                                        <option v-for="field in fields.users" :value="'users.' + field">(Users) {{field}}</option>
                                        <option v-for="field in fields.accounts" :value="'accounts.' + field">(Accounts) {{field}}</option>
                                        <option v-for="field in fields.transactions" :value="'transactions.' + field">(Transactions) {{field}}</option>
                                    </select>
                                    <select class="form-control" v-model="condition.operator">
                                        <option>></option>
                                        <option>>=</option>
                                        <option><</option>
                                        <option><=</option>
                                        <option>=</option>
                                    </select>
                                    <input class="form-control" type="text" placeholder="Value" v-model="condition.value">
                                </div>
                                <div align="center" v-if="error.condition">
                                    <small class="error-control">{{error.condition[0]}}</small>
                                </div>
                                <div align="center">
                                    <button class="btn btn-success" style="margin: 18px 0 0;" @click="addCondition">
                                        Add Condition
                                    </button>
                                </div>
                            </div> <!-- conditions -->
                            <div align="center">
                                <button class="btn btn-primary" style="width:50%;" @click="query">
                                    Run
                                </button>
                            </div> <!-- Run button -->
                            
                            <h1 class="h1 text-center bg-info" style="margin-top:18px;padding:9px 0;">
                                Query Results
                            </h1>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr class="success" style="text-transform:uppercase;">
                                            <th class="text-center" v-for="key in keys">{{key}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="result in results">
                                            <td class="text-center" v-for="key in keys">{{result[key]}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    </form>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</template>

<script>
    import {post} from '../helpers/api';
    import Flash from '../helpers/flash';
    
    export default {
        created() {

        },
        data() {
            return {
                tables: [],
                fields: {
                    users: ["user_id", "email", "first_name", "last_name", "address"],
                    accounts: ["account_id", "user_id", "balance", "date_created"],
                    transactions: ["transaction_id", "type", "amount", "date", "source", "destination"],
                },
                checkedFields: {
                    users: [],
                    accounts: [],
                    transactions: [],
                },
                conditions: [
                    {
                        index: 1,
                        field: "",
                        operator: "",
                        value: ""
                    }
                ],
                results: [],
                error: {},
                joins: [
                    {
                        field1:"",
                        field2:""
                    }
                ],
                raw: {statement:""},
                isProcessing: false,
            };
        },
        computed: {
            hasUsers() {
                var has = false;
                this.tables.forEach(table => {
                    if(table == 'users') has = true;
                });
                return has;
            },
            hasAccounts() {
                var has = false;
                this.tables.forEach(table => {
                    if(table == 'accounts') has = true;
                });
                return has;
            },
            hasTransactions() {
                var has = false;
                this.tables.forEach(table => {
                    if(table == 'transactions') has = true;
                });
                return has;
            },
            keys() {
                if(this.results.length>0) {
                    return Object.keys(this.results[0]);
                }
            }
        },
        methods: {
            addJoin() {
                this.joins.push({
                    field1:"",
                    field2:"",
                });
            },
            removeJoin() {
                this.joins.pop();
            },
            addCondition() {
                this.conditions.push({
                    index: this.conditions.length+1,
                    field:"",
                    operator:"",
                    value:"",
                });
            },
            removeCondition() {
                this.conditions.pop();
            },
            clear() {
                this.tables = [];
                this.checkedFields = {
                    users: [],
                    accounts: [],
                    transactions: [],
                };
                this.conditions = [
                    {
                        index: 1,
                        field: "",
                        operator: "",
                        value: ""
                    }
                ];
                this.results = [];
                this.raw.statement = "";
            },
            query() {
                this.error = {};
                this.isProcessing = true;
                let data = {
                    tables: this.tables,
                    fields: this.checkedFields,
                    conditions: this.conditions,
                    joins: this.joins,
                };
                post('/api/query', data)
                    .then((res) => {
                        console.log(res.data);
                        this.results = res.data.results;
                        this.isProcessing = false;
                    })
                    .catch((err) => {
                        this.clear();
                        this.error = err.response.data;
                        this.isProcessing = false;
                    });
            },
            rawQuery() {
                this.results = [];
                this.error = {};
                this.isProcessing = true;
                post('/api/raw', this.raw)
                    .then((res) => {
                        console.log(res.data);
                        Flash.setSuccess(res.data.message)
                        if(!res.data.noReturnValue) { this.results = res.data.results; }
                        this.isProcessing = false;
                    })
                    .catch((err) => {
                        this.clear();
                        this.error = err.response.data;
                        this.isProcessing = false;
                    });
            }
        },
    }

</script>

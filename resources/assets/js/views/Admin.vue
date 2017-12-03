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
                        <div class="form-group">
                            <div class="checkbox" align="center">
                                <h2 class="h2 text-center" style="border-bottom: 1px solid #ccc;">Tables</h2>
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
                        <div class="row">
                            <h2 class="h2 text-center" style="border-bottom: 1px solid #ccc; margin: 18px 9px 0;">Fields</h2>
                            <div class="col-md-4" v-if="hasUsers">
                                <div class="form-group">
                                    <h3 class="h3 text-center">Users</h3>
                                    <div class="checkbox" align="center" v-for="field in fields.users">
                                        <label>
                                            <input type="checkbox" v-model="checkedFields.users" :value="field"> {{field}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" v-if="hasAccounts">
                                <div class="form-group">
                                    <h3 class="h3 text-center">Accounts</h3>
                                    <div class="checkbox" align="center" v-for="field in fields.accounts">
                                        <label>
                                            <input type="checkbox" v-model="checkedFields.accounts" :value="field"> {{field}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" v-if="hasTransactions">
                                <div class="form-group">
                                    <h3 class="h3 text-center">Transactions</h3>
                                    <div class="checkbox" align="center" v-for="field in fields.transactions">
                                        <label>
                                            <input type="checkbox" v-model="checkedFields.transactions" :value="field"> {{field}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- field checks -->
                        <div class="form-group">
                            <div class="conditions" v-for="condition in conditions">
                                <div align="center">
                                    <label class="h3 text-center">Condition {{condition.index}}</label>
                                    <button class="btn btn-danger" style="position:absolute;right:18px;margin-top:18px;" @click="removeCondition">Remove</button>
                                </div>
                                <select class="form-control" v-model="condition.field">
                                    <option v-for="field in fields.users" :value="field">(Users) {{field}}</option>
                                    <option v-for="field in fields.accounts" :value="field">(Accounts) {{field}}</option>
                                    <option v-for="field in fields.transactions" :value="field">(Transactions) {{field}}</option>
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
                            <div align="center">
                                <button class="btn btn-success" style="margin: 18px 0 0;" @click="addCondition">
                                    Add Condition
                                </button>
                            </div>
                        </div> <!-- conditions -->
                        <div align="center">
                            <button class="btn btn-primary" style="width:50%;">
                                Run
                            </button>
                        </div> <!-- Run button -->
                    </form>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</template>

<script>
    import {post} from '../helpers/api';
    
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
                    users: ["user_id", "email", "first_name", "last_name", "address"],
                    accounts: ["account_id", "user_id", "balance", "date_created"],
                    transactions: ["transaction_id", "type", "amount", "date", "source", "destination"],
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
        },
        methods: {
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
            }
        },
    }

</script>

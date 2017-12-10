<template>
    <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form class="border" @submit.prevent="createTransaction">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" class="loader" v-show="isProcessing">
                        <div :class="{hideLoader: isProcessing}">
                            <h1 class="h1 text-center" style="margin: 18px 0 27px;">Make a Transaction</h1>
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" v-model="form.type">
                                    <option value="T">Transfer</option>
                                    <option value="W">Withdraw</option>
                                    <option value="D">Deposit</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" class="form-control" placeholder="Amount" v-model="form.amount">
                            </div>
                            <div class="form-group">
                                <label>Source</label>
                                <select class="form-control" v-model="form.source">
                                    <option v-for="result in results" :value="result.account_id">{{result.account_id}}: ({{result.first_name}}) {{result.email}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Destination</label>
                                <select class="form-control" v-model="form.destination">
                                    <option v-for="result in results" :value="result.account_id">{{result.account_id}}: ({{result.first_name}}) {{result.email}}</option>
                                </select>
                            </div>
                            <div align="center" style="margin-bottom: 18px;">
                                <button class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
</template>

<script>
    import Flash from '../../../helpers/flash';
    import {post} from '../../../helpers/api';

    export default {
        created() {

        },
        data() {
            return {
                form: {
                    type: '',
                    amount:'',
                    source:'',
                    destination:'',
                },
                results: [],
                error: {},
                isProcessing: false,
            };
        },
        methods: {
            createTransaction() {
                this.isProcessing = true;
                this.error = {};
                post('/api/admin/createTransaction', this.form)
                    .then((res) => {
                        if(res.data.created) {
                            Flash.setSuccess('Transaction made');
                            this.$router.push({path: '/admin/transactions'});
                        }
                        this.isProcessing = false;
                    })
                    .catch((err) => {
                        if(err.response.status == 422) {
                            Flash.setError(err.response.data.message);
                            this.error = err.response.data;
                        }
                        this.isProcessing = false;
                    });
            }
        },
        mounted() {
            post('/api/admin/accountsWithUsers', this.form)
                .then((res) => {
                    this.results = res.data.results;
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        }
    }
</script>

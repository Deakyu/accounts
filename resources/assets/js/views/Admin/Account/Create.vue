<template>
    <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form class="border" @submit.prevent="createAccount">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" class="loader" v-show="isProcessing">
                        <div :class="{hideLoader: isProcessing}">
                            <h1 class="h1 text-center" style="margin: 18px 0 27px;">Create an Account</h1>
                            <div class="form-group">
                                <label>User ID</label>
                                <select class="form-control" v-model="form.user_id">
                                    <option v-for="result in results" :value="result.USER_ID">{{result.USER_ID}}: {{result.FIRST_NAME + ' ' + result.LAST_NAME}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Balance</label>
                                <input type="number" class="form-control" placeholder="Balance" v-model="form.balance">
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
                    user_id: "",
                    balance: "",
                },
                results: [],
                error: {},
                isProcessing: false,
            };
        },
        methods: {
            createAccount() {
                this.isProcessing = true;
                this.error = {};
                post('/api/admin/createAccount', this.form)
                    .then((res) => {
                        if(res.data.created) {
                            Flash.setSuccess('Account Created');
                            this.$router.push({path: '/admin/accounts'});
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
            post('/api/admin/users', this.form)
                .then((res) => {
                    this.results = res.data.results;
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        }
    }
</script>

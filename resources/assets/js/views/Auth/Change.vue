<template>
    <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form class="border" @submit.prevent="change">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" class="loader" v-show="isProcessing">
                        <div :class="{hideLoader: isProcessing}">
                            <h1 class="h1 text-center" style="margin: 18px 0 27px;">Change Password</h1>
                            <div class="form-group">
                                <label>User Id</label>
                                <input type="number" class="form-control" placeholder="User Id" v-model="form.user_id">
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="Password" v-model="form.password">
                            </div>
                            <div align="center" style="margin-bottom: 18px;">
                                <button class="btn btn-primary">Change</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
</template>

<script>
    import Flash from '../../helpers/flash';
    import {post} from '../../helpers/api';

    export default {
        created() {

        },
        data() {
            return {
                form: {
                    user_id: '',
                    password: ''
                },
                error: {},
                isProcessing: false,
            };
        },
        methods: {
            change() {
                this.isProcessing = true;
                this.error = {};
                post('/api/change', this.form)
                    .then((res) => {
                        if(res.data.changed) Flash.setSuccess('Changed');
                        this.isProcessing = false;
                    })
                    .catch((err) => {
                        if(err.response.status == 422) {
                            Flash.setSuccess(err.response.data);
                            this.error = err.response.data;
                        }
                        this.isProcessing = false;
                    });
            }
        },
        mounted() {
            
        }
    }
</script>

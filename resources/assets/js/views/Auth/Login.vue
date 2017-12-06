<template>
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form class="border" @submit.prevent="login">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" class="loader" v-show="isProcessing">
                    <div :class="{hideLoader:isProcessing}">
                        <h1 class="h1 text-center" style="margin: 18px 0 27px;">Login</h1>
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" class="form-control" placeholder="Email" v-model="form.email">
                            <div align="center">
                                <small class="error-control" v-if="error.email">{{error.email[0]}}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" placeholder="Password" v-model="form.password">
                        </div>
                        <div align="center" style="margin-bottom: 18px;">
                            <button class="btn btn-primary">Login</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</template>

<script>
    import {post} from '../../helpers/api';
    import Flash from '../../helpers/flash';
    import Auth from '../../Auth/auth';

    export default {
        created() {

        },
        data() {
            return {
                form: {
                    email:'',
                    password:'',
                },
                error: {},
                isProcessing: false,
            };
        },
        methods: {
            login() {
                this.isProcessing = true;
                this.error = {};

                post('/api/login', this.form)
                    .then((res) => {
                        if(res.data.authenticated) {
                            Auth.set(res.data.api_token, res.data.user_id, res.data.is_admin, res.data.first_name);
                            if (Auth.state.is_admin == 'null') {
                                Flash.setSuccess('Logged In');
                                this.$router.push({path:"/accounts"});
                            } else {
                                Flash.setSuccess('Logged in as Administrator');
                                this.$router.push({path: '/admin'});
                            }
                        }
                        this.isProcessing = false;
                    })
                    .catch((err) => {
                        if(err.response.status == 422) {
                            this.error = err.response.data;
                        }
                        this.form.password="";
                        this.isProcessing = false;
                    });
            }
        },
        mounted() {
            
        }
    }
</script>

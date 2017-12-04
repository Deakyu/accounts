<template>
    <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form class="border" @submit.prevent="register">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" class="loader" v-show="isProcessing">
                        <div :class="{hideLoader: isProcessing}">
                            <h1 class="h1 text-center" style="margin: 18px 0 27px;">Register</h1>
                            <div class="form-group">
                                <label>Email address</label>
                                <input type="email" class="form-control" placeholder="Email" v-model="form.email">
                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" placeholder="First Name" v-model="form.first_name">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" placeholder="Last Name" v-model="form.last_name">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" placeholder="Address" v-model="form.address">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="Password" v-model="form.password">
                            </div>
                            <div align="center" style="margin-bottom: 18px;">
                                <button class="btn btn-primary">Register</button>
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
                    email: '',
                    first_name: '',
                    last_name: '',
                    address: '',
                    password: ''
                },
                error: {},
                isProcessing: false,
            };
        },
        methods: {
            register() {
                this.isProcessing = true;
                this.error = {};
                post('/api/register', this.form)
                    .then((res) => {
                        if(res.data.registered) {
                            Flash.setSuccess('Registered');
                            this.$router.push({path: '/login'});
                        }
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

<template>
    <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form class="border" @submit.prevent="updateUser">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif" class="loader" v-show="isProcessing">
                        <div :class="{hideLoader: isProcessing}">
                            <h1 class="h1 text-center" style="margin: 18px 0 27px;">Edit User</h1>
                            <div class="form-group">
                                <label>Email address</label>
                                <input type="email" class="form-control" placeholder="Email"  v-model="form.email">
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
                            <div align="center" style="margin-bottom: 18px;">
                                <button class="btn btn-primary">Update</button>
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
                    user_id: this.$route.params.id,
                    email: '',
                    first_name: '',
                    last_name: '',
                    address: '',
                },
                error: {},
                isProcessing: false,
            };
        },
        methods: {
            updateUser() {
                this.isProcessing = true;
                this.error = {};
                post('/api/admin/updateUser', this.form)
                    .then((res) => {
                        if(res.data.updated) Flash.setSuccess('User Updated');
                        this.isProcessing = false;
                        this.$router.push({path: '/admin/users'});
                    })
                    .catch((err) => {
                        if(err.response.status == 422) {
                            Flash.setSuccess(err.response.data.message);
                        }
                        this.isProcessing = false;
                    });
            }
        },
        mounted() {
            post('/api/admin/editUserInfo', {user_id: this.form.user_id})
                .then((res) => {
                    this.form.email = res.data.results[0].EMAIL;
                    this.form.first_name = res.data.results[0].FIRST_NAME;
                    this.form.last_name = res.data.results[0].LAST_NAME;
                    this.form.address = res.data.results[0].ADDRESS;
                })
                .catch((err) => {
                    console.error(err.response.data);
                });
        }
    }
</script>

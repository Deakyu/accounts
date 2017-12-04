<template>
<div>
    <nav class="navbar navbar-inverse" style="border-radius:0;">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <router-link to="/">
                    <a class="navbar-brand">Accounts Management</a>
                </router-link>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <router-link to="/accounts" tag="li" class="text-center">
                        <a v-if="check && !isAdmin">Accounts</a>
                    </router-link>
                    <router-link to="/transactions" tag="li" class="text-center">
                        <a v-if="check && !isAdmin">Transactions</a>
                    </router-link>
                    <router-link to="/login" tag="li" class="text-center">
                        <a v-if="!check" class="active">Login</a>
                    </router-link>
                    <router-link to="/register" tag="li" class="text-center">
                        <a v-if="!check">Register</a>
                    </router-link>
                    <router-link to="/admin" tag="li" class="text-center">
                        <a v-if="isAdmin">Admin</a>
                    </router-link>
                    <li class="text-center">
                        <a v-if="check" @click.stop="logout" style="cursor:pointer;">Logout</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="container">
        <div class="alert alert-success text-center" role="alert" v-if="flash.success">{{ flash.success }}</div>
        <div class="alert alert-danger text-center" role="alert" v-if="flash.error">{{ flash.error }}</div>
    </div>

    <router-view></router-view>
</div>
</template>

<script>
    import Auth from './Auth/auth';
    import Flash from './helpers/flash';
    import {post} from './helpers/api';

    export default {
        created() {
            Auth.initialize();
        },
        data() {
            return {
                flash: Flash.state,
                auth: Auth.state
            };
        },
        computed: {
            check() {
                return this.auth.api_token && this.auth.user_id;
            },
            isAdmin() {
                return this.auth.api_token && this.auth.is_admin != 'null';
            }
        },
        methods: {
            logout() {
                post('/api/logout')
                    .then((res) => {
                        if(res.data.logged_out) {
                            Auth.remove();
                            Flash.setSuccess('Logged Out');
                            this.$router.push('/');
                        }
                    })
                    .catch((err) => {
                        console.log(err.response);
                    });
            }
        },
        mounted() {

        }
    }
</script>

<template>
   <div class="container">
        <h1 class="h1 text-center" style="margin-bottom:54px;">
            Users
        </h1>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped" v-if="!aggregated">
                        <thead>
                            <tr class="success">
                                <th class="text-center">USER ID</th>
                                <th class="text-center">EMAIL</th>
                                <th class="text-center">FIRST NAME</th>
                                <th class="text-center">LAST NAME</th>
                                <th class="text-center">ADDRESS</th>
                                <th class="text-center">&nbsp;</th>
                                <th class="text-center">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="result in results">
                                <td class="text-center" v-for="key in keys">
                                    {{result[key]}}
                                </td>
                                <td class="text-center">
                                    <router-link :to="'/admin/users/edit/' + result.USER_ID" class="btn btn-info">
                                        EDIT
                                    </router-link>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger" @click="deleteUser(result.USER_ID)">
                                        DELETE
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <router-link to="/admin/users/create" tag="button" class="btn btn-primary" style="width:100%;margin-bottom:36px;">
                        Create a User
                    </router-link>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
  </div>
</template>

<script>
    import Auth from '../../../Auth/auth';
    import Flash from '../../../helpers/flash';
    import {post} from '../../../helpers/api';

    export default {
         data() {
            return {
                form: {
                    asc_desc: "ASC",
                    orderBy: "",
                    agg: "",
                    user_id: Auth.state.user_id,
                },
                results: [],
                aggregated: false,
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
            deleteUser(id) {
                post('/api/admin/deleteUser', {id:id})
                    .then((res) => {
                        Flash.setSuccess("User deleted");
                        this.results = res.data.results;
                    })
                    .catch((err) => {
                        console.error(err);
                    });
            },
        },
        mounted() {
            post('/api/admin/users', this.form)
                .then((res) => {
                    this.results = res.data.results;
                })
                .catch((err) => {

                });
        }
    }
</script>

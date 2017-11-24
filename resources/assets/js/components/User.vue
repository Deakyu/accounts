<template>
    <div class="container--user">
        <div class="container__left">&nbsp;</div>
        <div class="container__center">
            <form class="search search--users" @submit.prevent="searchUser">
                <input class="search__control" type="number" placeholder="ID" v-model="form.user_id">
                <input class="search__control" type="text" placeholder="First Name" v-model="form.first_name">
                <input class="search__control" type="text" placeholder="Last Name" v-model="form.last_name">
                <select class="search__control" v-model="form.sort_by">
                    <option value="" disabled></option>
                    <option value="ASC">Ascending</option>
                    <option value="DESC">Descending</option>
                </select>
                <button class="search__control search__button" :disabled="inputEmpty">Search</button>
            </form>
            <table class="results results--users">
                <tr class="results__header">
                    <td>ID</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td>Address</td>
                </tr>
                <tr v-for="user in users">
                    <td>{{user.USER_ID}}</td>
                    <td>{{user.FIRST_NAME}}</td>
                    <td>{{user.LAST_NAME}}</td>
                    <td>{{user.ADDRESS}}</td>
                </tr>
                <tr v-if="noUser">
                    <td>User Not Found</td>
                </tr>
            </table>
        </div>
        <div class="container__right">&nbsp;</div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    first_name: '',
                    last_name: '',
                    user_id: '',
                    sort_by: '',
                },
                users: []
            };
        },
        computed: {
            inputEmpty() {
                return this.form.first_name == '' && this.form.last_name == '' && this.form.user_id == '' && this.form.sort_by == '';
            },
            noUser() {
                return this.users.length <1;
            }
        },
        methods: {
            searchUser() {
                axios({
                    method: 'post',
                    url: '/api/users',
                    data: this.form
                })
                .then((res) => {
                    this.users = res.data.users;
                })
                .catch((err) => {

                });
            }
        },
        mounted() {
            console.log('User View Mounted.')
        }
    }
</script>

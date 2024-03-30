import AppListing from '../app-components/Listing/AppListing';

Vue.component('user-listing', {
    methods: {
        logout(user) {
            axios
                .post(`/admin/users/${user}/logout`)
                .then(function (response) {
                })
                .catch(function (errors) {
                    console.log(errors);
                });
        },
    },
    mixins: [AppListing]
});

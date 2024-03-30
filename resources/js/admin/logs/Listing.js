import AppListing from '../app-components/Listing/AppListing';

Vue.component('log-listing', {
    methods: {
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    },
    mixins: [AppListing]
});
import AppForm from '../app-components/Form/AppForm';

Vue.component('log-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                'id': '',
                'action':  '',
                'causer_id':  '',
                'loggable_id':  '',
                'loggable_type': '',
                'created_at': '',
            }
        }
    }

});

import AppForm from '../app-components/Form/AppForm';

Vue.component('solution-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                
            }
        }
    }

});
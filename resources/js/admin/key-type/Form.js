import AppForm from '../app-components/Form/AppForm';

Vue.component('key-type-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                
            }
        }
    }

});
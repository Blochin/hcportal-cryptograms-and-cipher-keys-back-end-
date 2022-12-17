import AppForm from '../app-components/Form/AppForm';

Vue.component('location-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                continent:  '' ,
                name:  '' ,
                
            }
        }
    }

});
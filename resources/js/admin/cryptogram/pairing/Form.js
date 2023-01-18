import AppForm from "../../app-components/Form/AppForm";

Vue.component("cryptogram-pairing-form", {
    mixins: [AppForm],
    data: function () {
        return {
            form: {
                cryptograms: [],
                keys: [],
            },
            isLoading: false,
            filteredCryptograms: [],
            filteredKeys: [],
        };
    },
    mounted() {},
    methods: {
        filterKeys(query) {
            let th = this;
            this.isLoading = true;
            axios
                .get("/admin/cipher-keys/search", {
                    params: {
                        search: query,
                    },
                })
                .then(
                    (response) => {
                        console.log(response.data);
                        th.filteredKeys = response.data;
                        th.isLoading = false;
                    },
                    (error) => {
                        console.log(error);
                        this.isLoading = false;
                    }
                );
        },

        filterCryptograms(query) {
            let th = this;
            this.isLoading = true;
            axios
                .get("/admin/cryptograms/search", {
                    params: {
                        search: query,
                    },
                })
                .then(
                    (response) => {
                        console.log(response.data);
                        th.filteredCryptograms = response.data;
                        th.isLoading = false;
                    },
                    (error) => {
                        console.log(error);
                        this.isLoading = false;
                    }
                );
        },
    },
});

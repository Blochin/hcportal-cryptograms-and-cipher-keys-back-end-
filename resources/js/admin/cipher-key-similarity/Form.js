import AppForm from "../app-components/Form/AppForm";

Vue.component("cipher-key-similarity-form", {
    mixins: [AppForm],
    props: ["cipherKeys"],
    data: function () {
        return {
            form: {
                name: "",
                cipher_keys: [],
            },
            isLoading: false,
            filteredCipherKeys: [],
        };
    },

    mounted() {
        this.filteredCipherKeys = this.cipherKeys;
    },
    methods: {
        setSimilarityName(e) {
            if (!this.form.name) {
                this.form.name = e[0]?.signature || e[0]?.complete_structure;
            }
        },
        filterCipherKeys(query) {
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
                        th.filteredCipherKeys = response.data;
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

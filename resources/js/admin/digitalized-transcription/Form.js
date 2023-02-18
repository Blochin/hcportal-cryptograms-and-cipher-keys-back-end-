import AppForm from "../app-components/Form/AppForm";

Vue.component("digitalized-transcription-form", {
    mixins: [AppForm],
    data: function () {
        return {
            form: {
                cipher_key: "",
                digitalized_version: "",
                note: "",
                digitalization_date: "",
                created_by: "",
                keys: "",
                encryption_pairs: [],
            },
            filteredKeys: [],
            isLoading: false,
        };
    },

    mounted() {
        this.convertTableToKeys();
        this.convertKeysToTable();
    },

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

        convertKeysToTable() {
            this.form.encryption_pairs = this.form.keys
                .split("\n")
                .map(function (e) {
                    return e.split(";").map(String);
                });
        },

        convertTableToKeys() {
            this.form.keys = this.form.encryption_pairs.join("\n");
            this.form.keys = this.form.keys.replaceAll(",", ";");
        },
    },
});

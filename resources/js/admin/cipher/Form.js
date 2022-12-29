import AppForm from "../app-components/Form/AppForm";

Vue.component("cipher-form", {
    mixins: [AppForm],
    props: ["tags"],
    data: function () {
        return {
            form: {
                availability: "",
                category_id: "",
                day: "",
                description: "",
                flag: false,
                image_url: "",
                language: "",
                location: "",
                month: "",
                name: "",
                recipient: "",
                sender: "",
                solution: "",
                state: "",
                year: "",
                groups: [],
                predefined_groups: [],
            },
            filteredTags: [],

            state: "",
            note: "",
        };
    },
    mounted() {
        this.filteredTags = this.tags;
    },
    methods: {
        addTag(newTag) {
            const tag = {
                name: newTag,
                type: "cipher",
            };

            axios
                .post("/admin/tags", tag)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (errors) {
                    console.log(errors);
                });

            this.filteredTags.push(tag);
            this.form.tags.push(tag);
        },
    },
});

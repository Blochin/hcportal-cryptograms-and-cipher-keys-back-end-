import AppForm from "../app-components/Form/AppForm";

Vue.component("cryptogram-form", {
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
                files: [],
                tags: [],
                cipher_keys: [],
            },
            filteredTags: [],
            filteredKeys: [],

            state: "",
            note: "",

            mediaCollections: ["picture"],
        };
    },
    mounted() {
        this.filteredTags = this.tags;
    },
    methods: {
        getPostData: function getPostData() {
            var _this3 = this;

            if (this.mediaCollections) {
                this.mediaCollections.forEach(function (
                    collection,
                    index,
                    arr
                ) {
                    if (_this3.form[collection]) {
                        console.warn(
                            "MediaUploader warning: Media input must have a unique name, '" +
                                collection +
                                "' is already defined in regular inputs."
                        );
                    }

                    if (_this3.$refs[collection + "_uploader"]) {
                        _this3.form[collection] =
                            _this3.$refs[collection + "_uploader"].getFiles();
                    }
                });
            }

            let formData = new FormData();

            var files = this.$refs.files;
            console.log(files);
            if (files) {
                var totalfiles = files.length;
                for (var index = 0; index < totalfiles; index++) {
                    formData.append(files[index].name, files[index].files[0]);
                    //this.form.files.push(files[index].files[0]);
                }
            }

            for (const [key, value] of Object.entries(this.form)) {
                let data = value;
                if (value instanceof Object) {
                    data = JSON.stringify(value);
                } else if (value == "false") {
                    data = false;
                } else if (value == "true") {
                    data = true;
                }
                if (
                    value != null ||
                    value == "undefined" ||
                    value == undefined
                ) {
                    formData.append(key, data);
                } else {
                    formData.append(key, "");
                }
            }

            formData.append("wysiwygMedia", this.wysiwygMedia);

            //this.form["files"] = formData;

            return formData;
        },
        onSubmit: function onSubmit() {
            var _this4 = this;

            return this.$validator.validateAll().then(function (result) {
                if (!result) {
                    _this4.$notify({
                        type: "error",
                        title: "Error!",
                        text: "The form contains invalid fields.",
                    });
                    return false;
                }

                var data = _this4.form;
                if (!_this4.sendEmptyLocales) {
                    data = _.omit(
                        _this4.form,
                        _this4.locales.filter(function (locale) {
                            return _.isEmpty(_this4.form[locale]);
                        })
                    );
                }

                _this4.submiting = true;
                const headers = { "Content-Type": "multipart/form-data" };
                axios
                    .post(_this4.action, _this4.getPostData(), { headers })
                    .then(function (response) {
                        return _this4.onSuccess(response.data);
                    })
                    .catch(function (errors) {
                        return _this4.onFail(errors.response.data);
                    });
            });
        },
        addTag(newTag) {
            let th = this;

            let tag = {
                name: newTag,
                type: "cryptogram",
            };

            axios
                .post("/admin/tags", tag)
                .then(function (response) {
                    tag = response.data.tag;
                    th.filteredTags.push(tag);
                    th.form.tags.push(tag);
                })
                .catch(function (errors) {
                    console.log(errors);
                });
        },
        addDatagroup: function (event) {
            const index = this.form.groups.push({
                description: "",
                data: [],
            });
        },

        deleteDatagroup: function (index) {
            this.form.groups.splice(index, 1);
        },

        addData: function (datagroupIndex) {
            console.log(datagroupIndex);
            this.form.groups[datagroupIndex].data.push({
                type: "",
                name: "",
                title: "",

                text: "",
                link: "",
                image: "",
            });
        },
        deleteData: function (datagroupIndex, index) {
            this.form.groups[datagroupIndex].data.splice(index, 1);
        },

        showUpdateState() {
            this.$modal.show("update-state");
        },
        hideUpdateState() {
            this.$modal.hide("update-state");
        },

        updateState() {
            let th = this;
            axios
                .post(this.data.resource_url + "/state", {
                    state: this.state.id,
                    note: this.note,
                })
                .then(function (response) {
                    th.hideUpdateState();
                    th.$notify({
                        type: "success",
                        title: "Success!",
                        text: "Cryptogram's state has been updated successfully.",
                    });
                    window.location.reload();
                })
                .catch(function (errors) {
                    return th.onFail(errors.response.data);
                });
        },

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
    },
});

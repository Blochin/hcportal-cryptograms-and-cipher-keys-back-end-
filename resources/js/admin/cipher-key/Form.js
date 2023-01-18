import AppForm from "../app-components/Form/AppForm";

Vue.component("cipher-key-form", {
    mixins: [AppForm],
    props: ["archives", "fonds", "folders", "tags"],
    data: function () {
        return {
            form: {
                description: "",
                signature: "",
                complete_structure: "",
                used_chars: "",
                cipher_type: "",
                key_type: "",
                used_from: "",
                used_to: "",
                used_around: "",
                folder: "",
                fond: "",
                archive: "",
                location: "",
                language: "",
                group: "",
                new_folder: "",
                new_fond: "",
                new_archive: "",
                users: [],
                images: [],
                files: [],
                tags: [],
                cryptograms: [],
            },
            filteredFonds: [],
            filteredFolders: [],
            filteredTags: [],
            filteredCryptograms: [],

            state: "",
            note: "",
        };
    },

    mounted() {
        // this.filteredFonds = this.fonds;
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
            if (files) {
                var totalfiles = files.length;
                for (var index = 0; index < totalfiles; index++) {
                    formData.append("files[]", files[index].files[0]);
                    this.form.files.push(files[index].files[0]);
                }
            }

            for (const [key, value] of Object.entries(this.form)) {
                console.log(key);
                console.log(value);
                let data = value;
                if (value instanceof Object) {
                    data = JSON.stringify(value);
                }
                if (value != null) {
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
                type: "cipher_key",
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
        addUser: function (event) {
            this.form.users.push({
                user: "",
                new_user: "",
                is_main_user: false,
            });
        },

        deleteUser: function (index) {
            this.form.users.splice(index, 1);
        },

        addCountUser: function (index) {
            this.form.users[index].is_main_user = false;
        },

        addImage: function (event) {
            this.form.images.push({
                id: this.form.images.length,
                has_instructions: false,
                structure: "",
            });
        },
        deleteImage: function (index) {
            this.form.images.splice(index, 1);
        },
        filterFonds() {
            this.form.fond = "";
            this.form.folder = "";
            this.filteredFonds = collect(this.fonds)
                .where("archive_id", this.form.archive.id)
                .toArray();
        },

        filterFolders() {
            this.form.folder = "";
            this.filteredFolders = collect(this.folders)
                .where("fond_id", this.form.fond.id)
                .toArray();
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
                        text: "Cipher Key state has been updated successfully.",
                    });
                    window.location.reload();
                })
                .catch(function (errors) {
                    return th.onFail(errors.response.data);
                });
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

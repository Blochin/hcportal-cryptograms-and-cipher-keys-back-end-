import AppForm from "../app-components/Form/AppForm";
import "trumbowyg/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js";
import "trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js";
import { Cropper, Preview } from "vue-advanced-cropper";

// This function is used to detect the actual image type,
function getMimeType(file, fallback = null) {
    const byteArray = new Uint8Array(file).subarray(0, 4);
    let header = "";
    for (let i = 0; i < byteArray.length; i++) {
        header += byteArray[i].toString(16);
    }
    switch (header) {
        case "89504e47":
            return "image/png";
        case "47494638":
            return "image/gif";
        case "ffd8ffe0":
        case "ffd8ffe1":
        case "ffd8ffe2":
        case "ffd8ffe3":
        case "ffd8ffe8":
            return "image/jpeg";
        default:
            return fallback;
    }
}

Vue.component("cryptogram-form", {
    mixins: [AppForm],
    props: ["tags", "categories", "persons", "archives", "fonds", "folders"],
    components: {
        Cropper,
        Preview,
    },
    data: function () {
        return {
            form: {
                availability: "",
                category: "",
                subcategory: "",
                description: "",
                date: "",
                date_around: "",
                image_url: "",
                language: "",
                location: "",

                name: "",
                recipient: "",
                sender: "",
                solution: "",
                state: "",
                groups: [],
                files: [],
                tags: [],
                cipher_keys: [],
                state: { id: "approved", title: "Approved" },
                note: "",
                thumbnail: "",
                availability_type: "archive",

                new_folder: "",
                new_fond: "",
                new_archive: "",

                folder: "",
                fond: "",
                archive: "",
            },
            filteredTags: [],
            filteredKeys: [],
            filteredCategories: [],
            filteredSubcategories: [],
            filteredUsers: [],
            filteredFonds: [],
            filteredFolders: [],

            state: "",
            note: "",
            isLoading: false,

            image: {
                src: null,
                type: null,
            },

            result: {
                coordinates: null,
                image: null,
            },

            mediaCollections: ["picture"],
            mediaWysiwygConfig: {
                autogrow: true,
                imageWidthModalEdit: true,
                removeformatPasted: true,
                btnsDef: {
                    image: {
                        dropdown: [],
                        ico: "insertImage",
                    },
                    align: {
                        dropdown: [
                            "justifyLeft",
                            "justifyCenter",
                            "justifyRight",
                            "justifyFull",
                        ],
                        ico: "justifyLeft",
                    },
                },
                btns: [
                    ["fontfamily"],
                    ["fontsize"],
                    ["formatting"],
                    ["strong", "em", "del"],
                    ["align"],
                    ["unorderedList", "orderedList", "table"],
                    ["foreColor", "backColor"],
                    ["link"],
                    ["template"],
                    ["fullscreen", "viewHTML"],
                ],
            },
        };
    },
    mounted() {
        this.filteredTags = this.tags;
        this.filteredCategories = this.categories;
        this.filteredSubcategories = this.form.category
            ? this.form.category.children
            : [];
        this.filteredUsers = this.persons;
    },
    methods: {
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

            //Data group files
            if (files) {
                var totalfiles = files.length;
                for (var index = 0; index < totalfiles; index++) {
                    formData.append(files[index].name, files[index].files[0]);
                    //this.form.files.push(files[index].files[0]);
                }
            }

            //Other data
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
                    data &&
                    (data !== null ||
                        data !== "null" ||
                        data == "undefined" ||
                        data == undefined)
                ) {
                    formData.append(key, data);
                } else {
                    formData.append(key, "");
                }
            }

            //Wysiwig media
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

        filterSubcategories(item) {
            this.form.subcategory = "";

            if (item.children.length > 0) {
                this.filteredSubcategories = item.children;
            } else {
                this.filteredSubcategories = [];
            }
        },

        crop() {
            let th = this;
            console.log(th.form.thumbnail);
            th.form.thumbnail = "";
            const { canvas } = this.$refs.cropper.getResult();
            canvas.toBlob((blob) => {
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function () {
                    th.form.thumbnail = reader.result;
                };
            }, this.image.type);

            console.log(th.form.thumbnail);

            this.$modal.hide("cropper-modal");
        },
        showCropModal() {
            this.$modal.show("cropper-modal");
        },
        reset() {
            this.image = {
                src: null,
                type: null,
            };
        },

        onChangeCrop({ coordinates, image }) {
            this.result = {
                coordinates,
                image,
            };
        },

        loadImage(event) {
            this.$modal.show("cropper-modal");

            // Reference to the DOM input element
            const { files } = event.target;
            // Ensure that you have a file before attempting to read it
            if (files && files[0]) {
                // 1. Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
                if (this.image.src) {
                    URL.revokeObjectURL(this.image.src);
                }
                // 2. Create the blob link to the file to optimize performance:
                const blob = URL.createObjectURL(files[0]);

                // 3. The steps below are designated to determine a file mime type to use it during the
                // getting of a cropped image from the canvas. You can replace it them by the following string,
                // but the type will be derived from the extension and it can lead to an incorrect result:
                //
                // this.image = {
                //    src: blob;
                //    type: files[0].type
                // }

                // Create a new FileReader to read this image binary data
                const reader = new FileReader();
                // Define a callback function to run, when FileReader finishes its job
                reader.onload = (e) => {
                    // Note: arrow function used here, so that "this.image" refers to the image of Vue component
                    this.image = {
                        // Set the image source (it will look like blob:http://example.com/2c5270a5-18b5-406e-a4fb-07427f5e7b94)
                        src: blob,
                        // Determine the image type to preserve it during the extracting the image from canvas:
                        type: getMimeType(e.target.result, files[0].type),
                    };
                };
                // Start the reader job - read file as a data url (base64 format)
                reader.readAsArrayBuffer(files[0]);
            }
        },

        addPredefinedGroups() {
            this.form.groups.push({
                description: "Links and references",
                data: [
                    {
                        type: { id: "link", name: "Link" },
                        name: "Links and references",
                        title: "Source",

                        text: "",
                        link: "",
                        image: "",
                    },
                ],
            });

            this.form.groups.push({
                description: "Transcription",
                data: [
                    {
                        type: { id: "text", name: "Text" },
                        name: "Transcription",
                        title: "Transcription",

                        text: "",
                        link: "",
                        image: "",
                    },
                ],
            });

            this.form.groups.push({
                description: "Cryptogram",
                data: [
                    {
                        type: { id: "image", name: "Image" },
                        name: "Cryptogram",
                        title: "Cryptogram image",

                        text: "",
                        link: "",
                        image: "",
                    },
                ],
            });
        },

        addUserPost(newUser, form = "sender") {
            let th = this;
            let user = {
                name: newUser,
            };

            axios
                .post("/admin/people", user)
                .then(function (response) {
                    let person = response.data.person;
                    th.filteredUsers.push(person);
                    th.form[form] = person;
                })
                .catch(function (errors) {
                    console.log(errors);
                });
        },
    },

    destroyed() {
        // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.image.src) {
            URL.revokeObjectURL(this.image.src);
        }
    },
});

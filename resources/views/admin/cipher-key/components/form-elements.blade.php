<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('cipher_type'), 'has-success': fields.cipher_type && fields.cipher_type.valid }">
            <label for="cipher_type" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.cipher_type') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.cipher_type" label="label" :options="{{ $cipherTypes }}"
                    :option-height="104" placeholder="{{ trans('admin.cipher-key.columns.cipher_type') }}"
                    track-by="id">
                </multiselect>
                <div v-if="errors.has('cipher_type')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('cipher_type') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('key_type'), 'has-success': fields.key_type && fields.key_type.valid }">
            <label for="key_type" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.key_type') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">
                <multiselect v-model="form.key_type" label="label" :options="{{ $keyTypes }}" :option-height="104"
                    placeholder="{{ trans('admin.cipher-key.columns.key_type') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('key_type')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('key_type') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('language'), 'has-success': fields.language && fields.language.valid }">
            <label for="language" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.language') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.language" label="name" :options="{{ $languages }}" :option-height="104"
                    placeholder="{{ trans('admin.cipher-key.columns.language') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('language')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('language') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('used_from'), 'has-success': fields.used_from && fields.used_from.valid }">
            <label for="used_from" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.used_from') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <div class="input-group input-group--custom">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <datetime v-model="form.used_from" :config="datetimePickerConfig" v-validate="''" class="flatpickr"
                        :class="{'form-control-danger': errors.has('used_from'), 'form-control-success': fields.used_from && fields.used_from.valid}"
                        id="used_from" name="used_from"
                        placeholder="{{ trans('brackets/admin-ui::admin.forms.select_date_and_time') }}"></datetime>
                </div>
                <div v-if="errors.has('used_from')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('used_from') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('used_to'), 'has-success': fields.used_to && fields.used_to.valid }">
            <label for="used_to" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.used_to') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <div class="input-group input-group--custom">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <datetime v-model="form.used_to" :config="datetimePickerConfig" v-validate="''" class="flatpickr"
                        :class="{'form-control-danger': errors.has('used_to'), 'form-control-success': fields.used_to && fields.used_to.valid}"
                        id="used_to" name="used_to"
                        placeholder="{{ trans('brackets/admin-ui::admin.forms.select_date_and_time') }}"></datetime>
                </div>
                <div v-if="errors.has('used_to')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('used_to') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('used_around'), 'has-success': fields.used_around && fields.used_around.valid }">
            <label for="used_around" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.used_around') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.used_around" v-validate="''" @input="validate($event)"
                    class="form-control"
                    :class="{'form-control-danger': errors.has('used_around'), 'form-control-success': fields.used_around && fields.used_around.valid}"
                    id="used_around" name="used_around"
                    placeholder="{{ trans('admin.cipher-key.columns.used_around') }}">
                <div v-if="errors.has('used_around')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('used_around') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('signature'), 'has-success': fields.signature && fields.signature.valid }">
            <label for="signature" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.signature') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <div>
                    <textarea class="form-control" v-model="form.signature" v-validate="''" id="signature"
                        name="signature"></textarea>
                </div>
                <div v-if="errors.has('signature')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('signature') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('group'), 'has-success': fields.group && fields.group.valid }">
            <label for="group" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.group') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.group" label="signature" :options="{{ $groups }}" :option-height="104"
                    placeholder="{{ trans('admin.cipher-key.columns.group') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('group')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('group') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('location'), 'has-success': fields.location && fields.location.valid }">
            <label for="location" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.location') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.location" label="name" :options="{{ $locations }}" :option-height="104"
                    placeholder="{{ trans('admin.cipher-key.columns.location') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('location')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('location') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('complete_structure'), 'has-success': fields.complete_structure && fields.complete_structure.valid }">
            <label for="complete_structure" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.complete_structure') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <div>
                    <textarea class="form-control" v-model="form.complete_structure" v-validate="''"
                        id="complete_structure" name="complete_structure"></textarea>
                </div>
                <div v-if="errors.has('complete_structure')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('complete_structure') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('used_chars'), 'has-success': fields.used_chars && fields.used_chars.valid }">
            <label for="used_chars" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.used_chars') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <div>
                    <textarea class="form-control" v-model="form.used_chars" v-validate="''" id="used_chars"
                        name="used_chars"></textarea>
                </div>
                <div v-if="errors.has('used_chars')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('used_chars') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('tags'), 'has-success': fields.tags && fields.tags.valid }">
            <label for="tags" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.tags') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.tags" tag-placeholder="Add this as new tag" placeholder="Search or add a tag"
                    :multiple="true" :taggable="true" @tag="addTag" label="name" :options="filteredTags"
                    :option-height="104" placeholder="{{ trans('admin.cipher-key.columns.tags') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('tags')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('tags') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('description'), 'has-success': fields.description && fields.description.valid }">
    <label for="description" class="col-form-label"
        :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.description') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
        <div>
            <wysiwyg v-model="form.description" v-validate="''" id="description" name="description"
                :config="mediaWysiwygConfig"></wysiwyg>
        </div>
        <div v-if="errors.has('description')" class="form-control-feedback form-text" v-cloak>
            @{{ errors . first('description') }}</div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('archive'), 'has-success': fields.archive && fields.archive.valid }">
            <label for="archive" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.archive') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.archive" @input="filterFonds" label="name" :options="{{ $archives }}"
                    :option-height="104" placeholder="{{ trans('admin.cipher-key.columns.archive') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('archive')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('archive') }}</div>
            </div>
        </div>
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('new_archive'), 'has-success': fields.new_archive && fields.new_archive.valid }">
            <label for="new_archive" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.new_archive') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.new_archive" v-validate="''" @input="validate($event)"
                    class="form-control"
                    :class="{'form-control-danger': errors.has('new_archive'), 'form-control-success': fields.new_archive && fields.new_archive.valid}"
                    id="new_archive" name="new_archive"
                    placeholder="{{ trans('admin.cipher-key.columns.new_archive') }}">
                <div v-if="errors.has('new_archive')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('new_archive') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('fond'), 'has-success': fields.fond && fields.fond.valid }">
            <label for="fond" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.folder') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.fond" label="name" @input="filterFolders" :options="filteredFonds"
                    :option-height="104" placeholder="{{ trans('admin.cipher-key.columns.fond') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('fond')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('fond') }}</div>
            </div>
        </div>
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('new_fond'), 'has-success': fields.new_fond && fields.new_fond.valid }">
            <label for="new_fond" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.new_fond') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.new_fond" v-validate="''" @input="validate($event)"
                    class="form-control"
                    :class="{'form-control-danger': errors.has('new_fond'), 'form-control-success': fields.new_fond && fields.new_fond.valid}"
                    id="new_fond" name="new_fond" placeholder="{{ trans('admin.cipher-key.columns.new_fond') }}">
                <div v-if="errors.has('new_fond')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('new_fond') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('folder'), 'has-success': fields.folder && fields.folder.valid }">
            <label for="folder" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.folder') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.folder" label="name" :options="filteredFolders" :option-height="104"
                    placeholder="{{ trans('admin.cipher-key.columns.folder') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('folder')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('folder') }}</div>
            </div>
        </div>
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('new_folder'), 'has-success': fields.new_folder && fields.new_folder.valid }">
            <label for="new_folder" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.new_folder') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.new_folder" v-validate="''" @input="validate($event)"
                    class="form-control"
                    :class="{'form-control-danger': errors.has('new_folder'), 'form-control-success': fields.new_folder && fields.new_folder.valid}"
                    id="new_folder" name="new_folder"
                    placeholder="{{ trans('admin.cipher-key.columns.new_folder') }}">
                <div v-if="errors.has('new_folder')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('new_folder') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div :class="form.users.length > 0 ? 'col-md-12' : 'col-md-4'">
        <div v-for="(input, index) in form.users" :key="index">
            <div class="form-group row align-items-end">
                <div class="col-md-4">
                    <label for="users" class="col-form-label">{{ trans('admin.cipher-key.columns.user') }}
                    </label>
                    <multiselect v-model="form.users[index].user" @input="" :data-vv-name="'user-' + index" label="name"
                        :select-label="''" :deselect-label="''" :options="{{ $users }}" track-by="id"
                        :multiple="false" :option-height="104"
                        placeholder="{{ trans('admin.cipher-key.columns.user') }}">
                    </multiselect>
                </div>
                <div class="col-md-4">
                    <label for="new_user" class="col-form-label">{{ trans('admin.cipher-key.columns.new_user') }}
                    </label>
                    <input type="text" v-model="form.users[index].new_user" v-validate="''" @input="validate($event)"
                        class="form-control"
                        :class="{'form-control-danger': errors.has('new_user'), 'form-control-success': fields.new_user && fields.new_user.valid}"
                        id="new_user" name="new_user" placeholder="{{ trans('admin.cipher-key.columns.new_user') }}">
                </div>
                <div class="col-md-3">
                    <div class="form-check row"
                        :class="{'has-danger': errors.has('is_main_user'), 'has-success': fields.is_main_user && fields.is_main_user.valid }">
                        <div class="ml-md-auto" :class="isFormLocalized ? 'col-md-8' : 'col-md-10'">
                            <input class="form-check-input" :id="'is_main_user' + index" type="checkbox"
                                v-model="form.users[index].is_main_user" v-validate="''" data-vv-name="is_main_user"
                                :name="'is_main_user' + index">
                            <label class="form-check-label" :for="'is_main_user' + index">
                                {{ trans('admin.cipher-key.columns.is_main_user') }}
                            </label>
                            <input type="hidden" name="is_main_user" :value="form.is_main_user">
                            <div v-if="errors.has('is_main_user')" class="form-control-feedback form-text" v-cloak>
                                @{{ errors . first('is_main_user') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <a v-on:click.stop="deleteUser(index)" class="btn btn-danger" style="color: white">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div :class="isFormLocalized ? 'col-md-12 text-center mt-3 mb-3' : 'col-md-12 col-xl-12 text-center mt-3 mb-3'">
        <a v-on:click.stop="addUser" class="btn btn-primary" style="color: white">
            <i class="fa fa-plus"></i> {{ trans('admin.cipher-key.columns.add_user') }}
        </a>
    </div>
</div>

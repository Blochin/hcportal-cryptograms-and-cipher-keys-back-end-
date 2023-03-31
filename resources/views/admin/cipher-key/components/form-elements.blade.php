<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
            <label for="name" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.name') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <div>
                    <textarea class="form-control" v-model="form.name" v-validate="'required'" id="name"
                        name="name"></textarea>
                </div>
                <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('name') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('category_id'), 'has-success': fields.category_id && fields.category_id.valid }">
            <label for="category_id" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.category_id') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.category" label="name" :options="{{ $categories->toJSON() }}"
                    :option-height="104" @select="filterSubcategories"
                    placeholder="{{ trans('admin.cryptogram.columns.category_id') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('category_id')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('category_id') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4" v-if="filteredSubcategories.length > 0 || form.subcategory">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('subcategory_id'), 'has-success': fields.subcategory_id && fields.subcategory_id.valid }">
            <label for="subcategory_id" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.subcategory_id') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.subcategory" label="name" :options="filteredSubcategories"
                    :option-height="104" placeholder="{{ trans('admin.cryptogram.columns.subcategory_id') }}"
                    track-by="id">
                </multiselect>
                <div v-if="errors.has('subcategory_id')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('subcategory_id') }}</div>
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
</div>
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('used_from'), 'has-success': fields.used_from && fields.used_from.valid }">
            <label for="used_from" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.used_from') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <div class="input-group input-group--custom">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <datetime v-model="form.used_from" :config="datePickerConfig" v-validate="''" class="flatpickr"
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
                    <datetime v-model="form.used_to" :config="datePickerConfig" v-validate="''" class="flatpickr"
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
    {{-- <div class="col-12 col-lg-4">
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
    </div> --}}
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('continent'), 'has-success': fields.continent && fields.continent.valid }">
            <label for="continent" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.continent') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.continent" label="name" :options="{{ $continents }}" :option-height="104"
                    placeholder="{{ trans('admin.cipher-key.columns.continent') }}" track-by="name">
                </multiselect>
                <div v-if="errors.has('continent')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('continent') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('location_name'), 'has-success': fields.location_name && fields.location_name.valid }">
            <label for="location_name" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.location') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.location_name" v-validate="''" @input="validate($event)"
                    class="form-control"
                    :class="{'form-control-danger': errors.has('location_name'), 'form-control-success': fields.location_name && fields.location_name.valid}"
                    id="location_name" name="location_name"
                    placeholder="{{ trans('admin.cipher-key.columns.location') }}">
                <div v-if="errors.has('location_name')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('location_name') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('tags'), 'has-success': fields.tags && fields.tags.valid }">
            <label for="tags" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.tags') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.tags" tag-placeholder="Add this as new tag" :close-on-select="false"
                    placeholder="Search or add a tag" :multiple="true" :taggable="true" @tag="addTag" label="name"
                    :options="filteredTags" :option-height="104"
                    placeholder="{{ trans('admin.cipher-key.columns.tags') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('tags')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('tags') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('state'), 'has-success': fields.state && fields.state.valid }">
            <label for="state" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.state') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.state" placeholder="State" label="title" :options="{{ $states }}"
                    :option-height="104" placeholder="{{ trans('admin.cipher-key.columns.state') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('state')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('state') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('note_new'), 'has-success': fields.note && fields.note.valid }">
            <label for="note" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.note') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <textarea class="form-control" v-model="form.note" disabled id="note" name="note"></textarea>
                <textarea class="form-control" v-model="form.note_new" v-validate="''" id="note" name="note"></textarea>
                <div v-if="errors.has('note_new')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('note_new') }}</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-lg-2">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('availability'), 'has-success': fields.availability_type && fields.availability_type.valid }">
            <label for="availability_type" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.availability_type') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <div class="d-inline-block mr-5">

                    <input type="radio" v-model="form.availability_type" value="archive" v-validate="'required'"
                        @input="validate($event)" class="form-control"
                        :class="{'': errors.has('availability_type'), '': fields.availability_type && fields.availability_type.valid}"
                        id="archive" name="archive">
                    <label for="archive" class="mt-2">Archive</label>
                </div>
                <div class="d-inline-block">
                    <input type="radio" v-model="form.availability_type" value="other" v-validate="'required'"
                        @input="validate($event)" class="form-control"
                        :class="{'': errors.has('availability_type'), '': fields.availability_type && fields.availability_type.valid}"
                        id="other" name="other">
                    <label for="other" class="mt-2">Other</label>
                </div>
                <div v-if="errors.has('availability_type')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('availability_type') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4" v-if="form.availability_type == 'other'">
        <div class=" form-group row align-items-center"
            :class="{'has-danger': errors.has('availability'), 'has-success': fields.availability && fields.availability.valid }">
            <label for="availability" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.availability') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.availability"
                    v-validate="form.availability_type == 'other' ? 'required' : ''" @input="validate($event)"
                    class="form-control"
                    :class="{'form-control-danger': errors.has('availability'), 'form-control-success': fields.availability && fields.availability.valid}"
                    id="availability" name="availability"
                    placeholder="{{ trans('admin.cryptogram.columns.availability') }}">
                <div v-if="errors.has('availability')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('availability') }}</div>
            </div>
        </div>
    </div>
</div>
<div class="row" v-if="form.availability_type == 'archive'">
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
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.fond') }}</label>
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
            :class="{'has-danger': errors.has('key_type'), 'has-success': fields.key_type && fields.key_type.valid }">
            <label for="key_type" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.key_type') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">
                <multiselect v-model="form.key_type" label="name" :options="{{ $keyTypes }}" :option-height="104"
                    placeholder="{{ trans('admin.cipher-key.columns.key_type') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('key_type')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('key_type') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('complete_structure'), 'has-success': fields.complete_structure && fields.complete_structure.valid }">
            <label for="complete_structure" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.complete_structure') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <div>
                    <textarea class="form-control" v-model="form.complete_structure" v-validate="'required'"
                        id="complete_structure" name="complete_structure"></textarea>
                </div>
                <div v-if="errors.has('complete_structure')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('complete_structure') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('cryptograms'), 'has-success': fields.cryptograms && fields.cryptograms.valid }">
            <label for="cryptograms" class="col-form-label"
                :class="isFormLocalized ? 'col-md-12' : 'col-md-12'">{{ trans('admin.cipher-key.columns.similar-cryptograms') }}</label>
            <div :class="isFormLocalized ? 'col-md-12' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.cryptograms" :close-on-select="false" placeholder="Search cryptograms"
                    :multiple="true" label="name" :loading="isLoading" :internal-search="false"
                    @search-change="filterCryptograms" :options="filteredCryptograms" :option-height="280"
                    placeholder="{{ trans('admin.cipher-key.columns.similar-cryptograms') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('cryptograms')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('cryptograms') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div :class="form.users.length > 0 ? 'col-md-12' : 'col-md-4'">
        <div v-for="(input, index) in form.users" :key="index">
            <div class="form-group row align-items-end">
                <div class="col-md-8">
                    <label for="users" class="col-form-label">{{ trans('admin.cipher-key.columns.user') }}
                    </label>
                    <multiselect v-model="form.users[index].user" tag-placeholder="Add this as new user"
                        placeholder="Search or add a user" :multiple="false" :taggable="true"
                        @tag="addUserPost($event, index)" label="name" :options="filteredUsers" :option-height="104"
                        placeholder="{{ trans('admin.cipher-key.columns.user') }}" track-by="id">
                    </multiselect>
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

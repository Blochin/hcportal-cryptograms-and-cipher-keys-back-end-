<div class="row d-flex align-items-center">
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
            <label for="name" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.name') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-12'">
                <input type="text" v-model="form.name" v-validate="''" @input="validate($event)" class="form-control"
                    :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}"
                    id="name" name="name" placeholder="{{ trans('admin.cryptogram.columns.name') }}">
                <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('name') }}
                </div>
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
                    :option-height="104" placeholder="{{ trans('admin.cryptogram.columns.category_id') }}"
                    track-by="id">
                </multiselect>
                <div v-if="errors.has('category_id')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('category_id') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('availability'), 'has-success': fields.availability && fields.availability.valid }">
            <label for="availability" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.availability') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.availability" v-validate="''" @input="validate($event)"
                    class="form-control"
                    :class="{'form-control-danger': errors.has('availability'), 'form-control-success': fields.availability && fields.availability.valid}"
                    id="availability" name="availability"
                    placeholder="{{ trans('admin.cryptogram.columns.availability') }}">
                <div v-if="errors.has('availability')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('availability') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('day'), 'has-success': fields.day && fields.day.valid }">
            <label for="day" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.day') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.day" v-validate="''" @input="validate($event)" class="form-control"
                    :class="{'form-control-danger': errors.has('day'), 'form-control-success': fields.day && fields.day.valid}"
                    id="day" name="day" placeholder="{{ trans('admin.cryptogram.columns.day') }}">
                <div v-if="errors.has('day')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('day') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('month'), 'has-success': fields.month && fields.month.valid }">
            <label for="month" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.month') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.month" v-validate="''" @input="validate($event)" class="form-control"
                    :class="{'form-control-danger': errors.has('month'), 'form-control-success': fields.month && fields.month.valid}"
                    id="month" name="month" placeholder="{{ trans('admin.cryptogram.columns.month') }}">
                <div v-if="errors.has('month')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('month') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('year'), 'has-success': fields.year && fields.year.valid }">
            <label for="year" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.year') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <input type="text" v-model="form.year" v-validate="''" @input="validate($event)" class="form-control"
                    :class="{'form-control-danger': errors.has('year'), 'form-control-success': fields.year && fields.year.valid}"
                    id="year" name="year" placeholder="{{ trans('admin.cryptogram.columns.year') }}">
                <div v-if="errors.has('year')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('year') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="form-check row"
            :class="{'has-danger': errors.has('flag'), 'has-success': fields.flag && fields.flag.valid }">
            <div class="ml-md-auto" :class="isFormLocalized ? 'col-md-8' : 'col-md-10'">
                <input class="form-check-input" id="flag" type="checkbox" v-model="form.flag" v-validate="''"
                    data-vv-name="flag" name="flag_fake_element">
                <label class="form-check-label" for="flag">
                    {{ trans('admin.cryptogram.columns.flag') }}
                </label>
                <input type="hidden" name="flag" :value="form.flag">
                <div v-if="errors.has('flag')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('flag') }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('language'), 'has-success': fields.language && fields.language.valid }">
            <label for="language" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.language_id') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.language" label="name" :options="{{ $languages }}" :option-height="104"
                    placeholder="{{ trans('admin.cryptogram.columns.language_id') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('language')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('language') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('location'), 'has-success': fields.location && fields.location.valid }">
            <label for="location" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.location_id') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.location" label="name" :options="{{ $locations }}" :option-height="104"
                    placeholder="{{ trans('admin.cryptogram.columns.location_id') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('location')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('location') }}</div>
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
                    placeholder="{{ trans('admin.cryptogram.columns.tags') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('tags')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('tags') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('recipient_id'), 'has-success': fields.recipient_id && fields.recipient_id.valid }">
            <label for="recipient_id" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.recipient_id') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.recipient" label="name" :options="{{ $persons->toJSON() }}"
                    :option-height="104" placeholder="{{ trans('admin.cryptogram.columns.recipient_id') }}"
                    track-by="id">
                </multiselect>
                <div v-if="errors.has('recipient_id')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('recipient_id') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('sender_id'), 'has-success': fields.sender_id && fields.sender_id.valid }">
            <label for="sender_id" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.sender_id') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.sender" label="name" :options="{{ $persons->toJSON() }}"
                    :option-height="104" placeholder="{{ trans('admin.cryptogram.columns.sender_id') }}"
                    track-by="id">
                </multiselect>
                <div v-if="errors.has('sender_id')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('sender_id') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('solution_id'), 'has-success': fields.solution_id && fields.solution_id.valid }">
            <label for="solution_id" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.solution_id') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.solution" label="name" :options="{{ $solutions->toJSON() }}"
                    :option-height="104" placeholder="{{ trans('admin.cryptogram.columns.solution_id') }}"
                    track-by="id">
                </multiselect>
                <div v-if="errors.has('solution_id')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('solution_id') }}</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-lg-12">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('cipher_keys'), 'has-success': fields.cipher_keys && fields.cipher_keys.valid }">
            <label for="cipher_keys" class="col-form-label"
                :class="isFormLocalized ? 'col-md-12' : 'col-md-12'">{{ trans('admin.cryptogram.columns.paired-keys') }}</label>
            <div :class="isFormLocalized ? 'col-md-12' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.cipher_keys" :close-on-select="false" placeholder="Search cipher key"
                    :multiple="true" label="signature" :loading="isLoading" :internal-search="false"
                    @search-change="filterKeys" :options="filteredKeys" :option-height="280"
                    placeholder="{{ trans('admin.cryptogram.columns.paired-keys') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('cipher_keys')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('cipher_keys') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('description'), 'has-success': fields.description && fields.description.valid }">
    <label for="description" class="col-form-label"
        :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.description') }}</label>
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
    <div :class="form.groups.length > 0 ? 'col-md-4' : 'col-md-4'" v-for="(input, index) in form.groups" :key="index">
        <div class="card">
            <div class="card-header">
                <div class="form-group row align-items-end">
                    <div class="col-10">
                        <label for="groups" class="col-form-label">{{ trans('admin.cryptogram.columns.group.name') }}
                        </label>
                        <input type="text" v-model="form.groups[index].description" v-validate="''"
                            @input="validate($event)" class="form-control"
                            :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name}"
                            :id="'name' + index" :name="'name' + index"
                            placeholder="{{ trans('admin.cryptogram.columns.group.name') }}">
                    </div>
                    <div class="col-2">
                        <a v-on:click.stop="deleteDatagroup(index)" class="btn btn-danger" style="color: white">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div v-for="(input, indexData) in form.groups[index].data" :key="'data'+indexData" class="row">
                    <div :class="form.groups[index].data.length > 0 ? 'col-md-12 card' : 'col-md-4'">
                        <div class="form-group row align-items-end">
                            <div class="col-10">
                                <label :for="'title'+ index + indexData+''"
                                    class="col-form-label">{{ trans('admin.cryptogram.columns.data.title') }}
                                </label>
                                <input type="text" v-model="form.groups[index].data[indexData].title" v-validate="''"
                                    @input="validate($event)" class="form-control"
                                    :class="{'form-control-danger': errors.has('title'+ index + indexData), 'form-control-success': fields.title+index+indexData && fields.title+index+indexData}"
                                    :id="'title'+ index + indexData" :name="'title'+ index + indexData"
                                    placeholder="{{ trans('admin.cryptogram.columns.data.title') }}">
                            </div>
                            <div class="col-2">
                                <a v-on:click.stop="deleteData(index, indexData)" class="btn btn-danger"
                                    style="color: white">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="form-group row align-items-end">
                            <div class="col-md-12">
                                <label for="groups"
                                    class="col-form-label">{{ trans('admin.cryptogram.columns.data.types') }}
                                </label>
                                <multiselect v-model="form.groups[index].data[indexData].type" label="name"
                                    :options="{{ collect(App\Models\Data::TYPES)->toJSON() }}" :option-height="104"
                                    placeholder="{{ trans('admin.cryptogram.columns.data.types') }}" track-by="id">
                                </multiselect>
                            </div>
                        </div>
                        <div class="form-group row align-items-end"
                            v-if="form.groups[index].data[indexData].type?.id == 'text'">
                            <div class="col-md-12">
                                <label :for="'text'+ index + indexData"
                                    class="col-form-label">{{ trans('admin.cryptogram.columns.data.text') }}
                                </label>
                                <textarea v-model="form.groups[index].data[indexData].text" v-validate="''"
                                    @input="validate($event)" class="form-control"
                                    :class="{'form-control-danger': errors.has('text'+ index + indexData), 'form-control-success': fields.text+index+indexData && fields.text+index+indexData}"
                                    :id="'text'+ index + indexData" :name="'text'+ index + indexData"> </textarea>
                            </div>
                        </div>
                        <div class="form-group row align-items-end"
                            v-if="form.groups[index].data[indexData].type?.id == 'link'">
                            <div class="col-md-12">
                                <label :for="'link'+ index + indexData"
                                    class="col-form-label">{{ trans('admin.cryptogram.columns.data.link') }}
                                </label>
                                <input type="text" v-model="form.groups[index].data[indexData].link" v-validate="''"
                                    @input="validate($event)" class="form-control"
                                    :class="{'form-control-danger': errors.has('link'+ index + indexData), 'form-control-success': fields.link+index+indexData && fields.link+index+indexData}"
                                    :id="'title'+ index + indexData" :name="'title'+ index + indexData"
                                    placeholder="{{ trans('admin.cryptogram.columns.data.link') }}">
                            </div>
                        </div>

                        <div class="form-group row align-items-end"
                            v-if="form.groups[index].data[indexData].type?.id == 'image'">
                            <div class="col-md-10">
                                <label :for="'link'+ index + indexData+''"
                                    class="col-form-label">{{ trans('admin.cryptogram.columns.data.image') }}
                                </label>
                                <input type="file" class="form-control" :id="'images['+index+']['+ indexData+']'"
                                    v-validate="''" :name="'images['+index+']['+ indexData+']'" :ref="'files'" />
                            </div>
                            <div class="col-md-2" v-if="form.groups[index].data[indexData].image">
                                <a :href="form.groups[index].data[indexData].image"><img
                                        :src="form.groups[index].data[indexData].image"
                                        :alt="form.groups[index].data[indexData].title" width="30px">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div
                    :class="isFormLocalized ? 'col-md-12 text-center mt-3 mb-3' : 'col-md-12 col-xl-12 text-center mt-3 mb-3'">
                    <a v-on:click.prevent="addData(index)" class="btn btn-primary" style="color: white">
                        <i class="fa fa-plus"></i> {{ trans('admin.cryptogram.columns.add_data') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div :class="isFormLocalized ? 'col-md-12 text-center mt-3 mb-3' : 'col-md-12 col-xl-12 text-center mt-3 mb-3'">
        <a v-on:click.prevent="addDatagroup" class="btn btn-primary" style="color: white">
            <i class="fa fa-plus"></i> {{ trans('admin.cryptogram.columns.add_datagroup') }}
        </a>
    </div>
</div>
{{-- <div class="row">
    <div class="col-12">
        <div class="form-group row align-items-center"
            :class="{'has-danger': errors.has('tags'), 'has-success': fields.tags && fields.tags.valid }">
            <label for="tags" class="col-form-label"
                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cryptogram.columns.predefined_groups') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                <multiselect v-model="form.predefined_groups" :close-on-select="false"
                    placeholder="Search predefined groups" :multiple="true" :taggable="true" label="description"
                    :options="{{ $groups->toJSON() }}" :option-height="104"
                    placeholder="{{ trans('admin.cryptogram.columns.predefined_groups') }}" track-by="id">
                </multiselect>
                <div v-if="errors.has('tags')" class="form-control-feedback form-text" v-cloak>
                    @{{ errors . first('tags') }}</div>
            </div>
        </div>
    </div>
</div> --}}

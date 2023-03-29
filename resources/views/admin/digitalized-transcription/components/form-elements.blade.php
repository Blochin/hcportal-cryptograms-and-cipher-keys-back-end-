<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('cipher_key'), 'has-success': fields.cipher_keys && fields.cipher_keys.valid }">
    <label for="cipher_keys" class="col-form-label text-md-right"
        :class="isFormLocalized ? 'col-md-12' : 'col-md-2'">{{ trans('admin.digitalized-transcription.columns.cipher-keys') }}</label>
    <div :class="isFormLocalized ? 'col-md-12' : 'col-md-9 col-xl-8'">
        <multiselect v-model="form.cipher_key" :close-on-select="true" placeholder="Search cipher key" :multiple="false"
            label="name" :loading="isLoading" :internal-search="false" @search-change="filterKeys"
            :options="filteredKeys" :option-height="280"
            placeholder="{{ trans('admin.digitalized-transcription.columns.cipher-keys') }}" track-by="id">
        </multiselect>
        <div v-if="errors.has('cipher_key')" class="form-control-feedback form-text" v-cloak>
            @{{ errors . first('cipher_key') }}</div>
    </div>
</div>

<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('digitalized_version'), 'has-success': fields.digitalized_version && fields.digitalized_version.valid }">
    <label for="digitalized_version" class="col-form-label text-md-right"
        :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.digitalized-transcription.columns.digitalized_version') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.digitalized_version" v-validate="''" @input="validate($event)"
            class="form-control"
            :class="{'form-control-danger': errors.has('digitalized_version'), 'form-control-success': fields.digitalized_version && fields.digitalized_version.valid}"
            id="digitalized_version" name="digitalized_version"
            placeholder="{{ trans('admin.digitalized-transcription.columns.digitalized_version') }}">
        <div v-if="errors.has('digitalized_version')" class="form-control-feedback form-text" v-cloak>
            @{{ errors . first('digitalized_version') }}</div>
    </div>
</div>

<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('note'), 'has-success': fields.note && fields.note.valid }">
    <label for="note" class="col-form-label text-md-right"
        :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.digitalized-transcription.columns.note') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div>
            <textarea class="form-control" v-model="form.note" v-validate="''" id="note" name="note"></textarea>
        </div>
        <div v-if="errors.has('note')" class="form-control-feedback form-text" v-cloak>@{{ errors . first('note') }}
        </div>
    </div>
</div>

<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('digitalization_date'), 'has-success': fields.digitalization_date && fields.digitalization_date.valid }">
    <label for="digitalization_date" class="col-form-label text-md-right"
        :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.digitalized-transcription.columns.digitalization_date') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div class="input-group input-group--custom">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            <datetime v-model="form.digitalization_date" :config="datetimePickerConfig"
                v-validate="'date_format:yyyy-MM-dd HH:mm:ss'" class="flatpickr"
                :class="{'form-control-danger': errors.has('digitalization_date'), 'form-control-success': fields.digitalization_date && fields.digitalization_date.valid}"
                id="digitalization_date" name="digitalization_date"
                placeholder="{{ trans('brackets/admin-ui::admin.forms.select_date_and_time') }}"></datetime>
        </div>
        <div v-if="errors.has('digitalization_date')" class="form-control-feedback form-text" v-cloak>
            @{{ errors . first('digitalization_date') }}</div>
    </div>
</div>

<div class="form-group row"
    :class="{'has-danger': errors.has('keys'), 'has-success': fields.keys && fields.keys.valid }">
    <label for="keys" class="col-form-label"
        :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.digitalized-transcription.columns.keys') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-6 col-xl-6'">
        <div>
            <textarea class="form-control" @change="convertKeysToTable" cols="3" rows="9"
                v-model="form.keys"></textarea>
            <small>Plain text unit;Cipher text unit</small>
        </div>
    </div>
    <div class="col-md-4 col-xl-4 ">
        <div class="row" v-if="form.encryption_pairs.length > 0">
            <div class="col-md-6"><b>Plain text unit</b></div>
            <div class="col-md-6"><b>Cipher text unit</b></div>
        </div>
        <div class="row" v-for="(value, key) in form.encryption_pairs">
            <div class="col-md-6"><input type="text" class="form-control" @input="convertTableToKeys"
                    v-model="form.encryption_pairs[key][0]">
            </div>
            <div class="col-md-6"><input type="text" class="form-control" @input="convertTableToKeys"
                    v-model="form.encryption_pairs[key][1]">
            </div>
        </div>
    </div>
</div>

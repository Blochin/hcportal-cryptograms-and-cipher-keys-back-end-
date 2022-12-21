<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label"
        :class="isFormLocalized ? 'col-md-12' : 'col-md-12'">{{ trans('admin.cipher-key-similarity.columns.name') }}</label>
    <div :class="isFormLocalized ? 'col-md-12' : 'col-md-12 col-xl-12'">
        <input type="text" v-model="form.name" v-validate="'required'" @input="validate($event)" class="form-control"
            :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}"
            id="name" name="name" placeholder="{{ trans('admin.cipher-key-similarity.columns.name') }}">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors . first('name') }}
        </div>
    </div>
</div>
<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('cipher_keys'), 'has-success': fields.cipher_keys && fields.cipher_keys.valid }">
    <label for="cipher_keys" class="col-form-label"
        :class="isFormLocalized ? 'col-md-12' : 'col-md-12'">{{ trans('admin.cipher-key-similarity.columns.cipher_keys') }}</label>
    <div :class="isFormLocalized ? 'col-md-12' : 'col-md-12 col-xl-12'">
        <multiselect v-model="form.cipher_keys" :close-on-select="false" placeholder="Search cipher key"
            :multiple="true" @input="setSimilarityName($event)" label="signature" :loading="isLoading"
            :internal-search="false" @search-change="filterCipherKeys" :options="filteredCipherKeys"
            :option-height="280" placeholder="{{ trans('admin.cipher-key-similarity.columns.cipher_keys') }}"
            track-by="id">
        </multiselect>
        <div v-if="errors.has('cipher_keys')" class="form-control-feedback form-text" v-cloak>
            @{{ errors . first('cipher_keys') }}</div>
    </div>
</div>

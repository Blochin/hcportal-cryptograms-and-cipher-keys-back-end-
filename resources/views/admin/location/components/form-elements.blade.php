<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('name'), 'has-success': fields.name && fields.name.valid }">
    <label for="name" class="col-form-label text-md-right"
        :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.location.columns.name') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.name" v-validate="'required'" @input="validate($event)" class="form-control"
            :class="{'form-control-danger': errors.has('name'), 'form-control-success': fields.name && fields.name.valid}"
            id="name" name="name" placeholder="{{ trans('admin.location.columns.name') }}">
        <div v-if="errors.has('name')" class="form-control-feedback form-text" v-cloak>@{{ errors . first('name') }}
        </div>
    </div>
</div>
<div class="form-group row align-items-center"
    :class="{'has-danger': errors.has('continent'), 'has-success': fields.continent && fields.continent.valid }">
    <label for="continent" class="col-form-label text-md-right"
        :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.location.columns.continent') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.continent" v-validate="''" @input="validate($event)" class="form-control"
            :class="{'form-control-danger': errors.has('continent'), 'form-control-success': fields.continent && fields.continent.valid}"
            id="continent" name="continent" placeholder="{{ trans('admin.location.columns.continent') }}">
        <div v-if="errors.has('continent')" class="form-control-feedback form-text" v-cloak>
            @{{ errors . first('continent') }}</div>
    </div>
</div>

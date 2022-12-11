<div class="row">
    <div :class="form.images.length > 0 ? 'col-md-12' : 'col-md-4'">
        <div v-for="(input, index) in form.images" :key="index">
            <div class="form-group row align-items-center">
                <div class="col-md-4">
                    <label for="images" class="col-form-label">{{ trans('admin.cipher-key.columns.image') }}
                    </label>
                    <input type="file" id="images[]" v-validate="'required'" name="images[]" ref="files" />
                </div>
                <div class="col-md-4">
                    <label for="structure"
                        class="col-form-label">{{ trans('admin.cipher-key.columns.image_structure') }}
                    </label>
                    <input type="text" v-model="form.images[index].structure" v-validate="''" @input="validate($event)"
                        class="form-control"
                        :class="{'form-control-danger': errors.has('structure'), 'form-control-success': fields.structure && fields.structure.valid}"
                        id="structure" name="structure"
                        placeholder="{{ trans('admin.cipher-key.columns.image_structure') }}">
                </div>
                <div class="col-md-3">
                    <div class="form-check row"
                        :class="{'has-danger': errors.has('has_instructions'), 'has-success': fields.has_instructions && fields.has_instructions.valid }">
                        <div class="ml-md-auto" :class="isFormLocalized ? 'col-md-8' : 'col-md-10'">
                            <input class="form-check-input" :id="'has_instructions' + index" type="checkbox"
                                v-model="form.images[index].has_instructions" v-validate="''"
                                data-vv-name="has_instructions" name="has_instructions_fake_element">
                            <label class="form-check-label" :for="'has_instructions' + index">
                                {{ trans('admin.cipher-key.columns.has_instructions') }}
                            </label>
                            <input type="hidden" name="has_instructions" :value="form.has_instructions">
                            <div v-if="errors.has('has_instructions')" class="form-control-feedback form-text" v-cloak>
                                @{{ errors . first('has_instructions') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <a v-on:click.stop="deleteImage(index)" class="btn btn-danger" style="color: white">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div :class="isFormLocalized ? 'col-md-12 text-center mt-3 mb-3' : 'col-md-12 col-xl-12 text-center mt-3 mb-3'">
        <a v-on:click.stop="addImage" class="btn btn-primary" style="color: white">
            <i class="fa fa-plus"></i> {{ trans('admin.cipher-key.columns.add_image') }}
        </a>
    </div>
</div>

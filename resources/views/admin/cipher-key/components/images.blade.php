<div class="row">
    <label for="key_type" class="col-form-label"
        :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.images') }}</label>
    <div class="col-3 col-lg-3" v-for="(item, index) in form.images" :key="item.id">
        <a :href="item.picture">
            <img :src="item.picture" alt="item.structure" width="100%">
        </a>
        <p class="m-0 mt-2"><b>Has instructions:</b> @{{ item . has_instructions ? 'Yes' : 'No' }}</p>
        <p class="m-0 mt-1"><b>Structure:</b> @{{ item . structure }}</p>
    </div>
</div>

<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">{{ trans('admin.sidebar.general') }}</li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/locations') }}"><i
                        class="nav-icon icon-ghost"></i> {{ trans('admin.location.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/tags') }}"><i
                        class="nav-icon icon-compass"></i> {{ trans('admin.tag.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/people') }}"><i
                        class="nav-icon icon-ghost"></i> {{ trans('admin.person.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/languages') }}"><i
                        class="nav-icon icon-book-open"></i> {{ trans('admin.language.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/categories') }}"><i
                        class="nav-icon icon-magnet"></i> {{ trans('admin.category.title') }}</a></li>
            <li class="nav-title">{{ trans('admin.sidebar.cipherkeys') }}</li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/cipher-keys') }}"><i
                        class="nav-icon icon-book-open"></i> {{ trans('admin.cipher-key.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/cipher-key-similarities') }}"><i
                        class="nav-icon icon-compass"></i> {{ trans('admin.cipher-key-similarity.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/key-types') }}"><i
                        class="nav-icon icon-puzzle"></i> {{ trans('admin.key-type.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/digitalized-transcriptions') }}"><i
                        class="nav-icon icon-plane"></i> {{ trans('admin.digitalized-transcription.title') }}</a>
            </li>
            {{-- Do not delete me :) I'm used for auto-generation menu items --}}

            <li class="nav-title">{{ trans('admin.sidebar.cryptograms') }}</li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/cryptograms') }}"><i
                        class="nav-icon icon-puzzle"></i> {{ trans('admin.cryptogram.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/solutions') }}"><i
                        class="nav-icon icon-globe"></i> {{ trans('admin.solution.title') }}</a></li>

            <li class="nav-title">{{ trans('brackets/admin-ui::admin.sidebar.settings') }}</li>

            <li class="nav-item"><a class="nav-link" href="{{ url('admin/users') }}"><i
                        class="nav-icon icon-user"></i> {{ trans('admin.user.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/logs') }}"><i
                        class="nav-icon icon-settings"></i> {{ trans('admin.logs.title') }}</a></li>
            {{-- Do not delete me :) I'm also used for auto-generation menu items --}}
            {{-- <li class="nav-item"><a class="nav-link" href="{{ url('admin/configuration') }}"><i class="nav-icon icon-settings"></i> {{ __('ConfigurationController') }}</a></li> --}}
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>

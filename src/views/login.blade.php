<form method="POST" action="{{{ Confide::checkAction('UserController@do_login') ?: URL::to('/user/login') }}}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
    <fieldset>
        <div class="form-group">
            <label for="email">{{{ __('confide::confide.username_e_mail') }}}</label>
            <input class="form-control" tabindex="1" placeholder="{{{ __('confide::confide.username_e_mail') }}}" type="text" name="email" id="email" value="{{{ request()->old('email') }}}">
        </div>
        <div class="form-group">
        <label for="password">
            {{{ __('confide::confide.password') }}}
            <small>
                <a href="{{{ (Confide::checkAction('UserController@forgot_password')) ?: 'forgot' }}}">{{{ __('confide::confide.login.forgot_password') }}}</a>
            </small>
        </label>
        <input class="form-control" tabindex="2" placeholder="{{{ __('confide::confide.password') }}}" type="password" name="password" id="password">
        </div>
        <div class="form-group">
            <label for="remember" class="checkbox">{{{ __('confide::confide.login.remember') }}}
                <input type="hidden" name="remember" value="0">
                <input tabindex="4" type="checkbox" name="remember" id="remember" value="1">
            </label>
        </div>
        @if ( Session::get('error') )
            <div class="alert alert-error">{{{ Session::get('error') }}}</div>
        @endif

        @if ( Session::get('notice') )
            <div class="alert">{{{ Session::get('notice') }}}</div>
        @endif
        <div class="form-group">
            <button tabindex="3" type="submit" class="btn btn-default">{{{ __('confide::confide.login.submit') }}}</button>
        </div>
    </fieldset>
</form>

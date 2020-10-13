<form method="POST" action="{{{ (Confide::checkAction('UserController@store')) ?: URL::to('user')  }}}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
    <fieldset>
        <div class="form-group">
            <label for="username">{{{ __('confide::confide.username') }}}</label>
            <input class="form-control" placeholder="{{{ __('confide::confide.username') }}}" type="text" name="username" id="username" value="{{{ request()->old('username') }}}">
        </div>
        <div class="form-group">
            <label for="email">{{{ __('confide::confide.e_mail') }}} <small>{{ __('confide::confide.signup.confirmation_required') }}</small></label>
            <input class="form-control" placeholder="{{{ __('confide::confide.e_mail') }}}" type="text" name="email" id="email" value="{{{ request()->old('email') }}}">
        </div>
        <div class="form-group">
            <label for="password">{{{ __('confide::confide.password') }}}</label>
            <input class="form-control" placeholder="{{{ __('confide::confide.password') }}}" type="password" name="password" id="password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">{{{ __('confide::confide.password_confirmation') }}}</label>
            <input class="form-control" placeholder="{{{ __('confide::confide.password_confirmation') }}}" type="password" name="password_confirmation" id="password_confirmation">
        </div>

        @if ( Session::get('error') )
            <div class="alert alert-error alert-danger">
                @if ( is_array(Session::get('error')) )
                    {{ head(Session::get('error')) }}
                @endif
            </div>
        @endif

        @if ( Session::get('notice') )
            <div class="alert">{{ Session::get('notice') }}</div>
        @endif

        <div class="form-actions form-group">
          <button type="submit" class="btn btn-primary">{{{ __('confide::confide.signup.submit') }}}</button>
        </div>

    </fieldset>
</form>

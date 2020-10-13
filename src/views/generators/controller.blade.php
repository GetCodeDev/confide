<?php echo "<?php\n"; ?>

/*
|--------------------------------------------------------------------------
| Confide Controller Template
|--------------------------------------------------------------------------
|
| This is the default Confide controller template for controlling user
| authentication. Feel free to change to your needs.
|
*/

class {{ $name }} extends BaseController {

    /**
     * Displays the form for account creation
     *
     */
    public function {{ (! $restful) ? 'create' : 'getCreate' }}()
    {
        return View::make(Config::get('confide::signup_form'));
    }

    /**
     * Stores new account
     *
     */
    public function {{ (! $restful) ? 'store' : 'postIndex' }}()
    {
        ${{ lcfirst(Config::get('auth.providers.users.model')) }} = new {{ Config::get('auth.providers.users.model') }};

        ${{ lcfirst(Config::get('auth.providers.users.model')) }}->username = request( 'username' );
        ${{ lcfirst(Config::get('auth.providers.users.model')) }}->email = request( 'email' );
        ${{ lcfirst(Config::get('auth.providers.users.model')) }}->password = request( 'password' );

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        ${{ lcfirst(Config::get('auth.providers.users.model')) }}->password_confirmation = request( 'password_confirmation' );

        // Save if valid. Password field will be hashed before save
        ${{ lcfirst(Config::get('auth.providers.users.model')) }}->save();

        if ( ${{ lcfirst(Config::get('auth.providers.users.model')) }}->getKey() )
        {
            @if ( Config::get('confide::signup_confirm') && Config::get('confide::signup_email'))
            $notice = __('confide::confide.alerts.account_created') . ' ' . __('confide::confide.alerts.instructions_sent');
            @else
            $notice = __('confide::confide.alerts.account_created');
            @endif

            // Redirect with success message, You may replace "__(..." for your custom message.
            @if (! $restful)
            return Redirect::action('{{ $name }}@login')
            @else
            return Redirect::to('user/login')
            @endif
                ->with( 'notice', $notice );
        }
        else
        {
            // Get validation errors (see Ardent package)
            $error = ${{ lcfirst(Config::get('auth.providers.users.model')) }}->errors()->all(':message');

            @if (! $restful)
            return Redirect::action('{{ $name }}@create')
            @else
            return Redirect::to('user/create')
            @endif
                ->withInput(request()->except('password'))
                ->with( 'error', $error );
        }
    }

    /**
     * Displays the login form
     *
     */
    public function {{ (! $restful) ? 'login' : 'getLogin' }}()
    {
        if( Confide::user() )
        {
            // If user is logged, redirect to internal
            // page, change it to '/admin', '/dashboard' or something
            return Redirect::to('/');
        }
        else
        {
            return View::make(Config::get('confide::login_form'));
        }
    }

    /**
     * Attempt to do login
     *
     */
    public function {{ (! $restful) ? 'do_login' : 'postLogin' }}()
    {
        $input = array(
            'email'    => request( 'email' ), // May be the username too
            'username' => request( 'email' ), // so we have to pass both
            'password' => request( 'password' ),
            'remember' => request( 'remember' ),
        );

        // If you wish to only allow login from confirmed users, call logAttempt
        // with the second parameter as true.
        // logAttempt will check if the 'email' perhaps is the username.
        // Get the value from the config file instead of changing the controller
        if ( Confide::logAttempt( $input, Config::get('confide::signup_confirm') ) )
        {
            // Redirect the user to the URL they were trying to access before
            // caught by the authentication filter IE Redirect::guest('user/login').
            // Otherwise fallback to '/'
            // Fix pull #145
            return Redirect::intended('/'); // change it to '/admin', '/dashboard' or something
        }
        else
        {
            ${{ lcfirst(Config::get('auth.providers.users.model')) }} = new {{ Config::get('auth.providers.users.model') }};

            // Check if there was too many login attempts
            if( Confide::isThrottled( $input ) )
            {
                $err_msg = __('confide::confide.alerts.too_many_attempts');
            }
            elseif( ${{ lcfirst(Config::get('auth.providers.users.model')) }}->checkUserExists( $input ) and ! ${{ lcfirst(Config::get('auth.providers.users.model')) }}->isConfirmed( $input ) )
            {
                $err_msg = __('confide::confide.alerts.not_confirmed');
            }
            else
            {
                $err_msg = __('confide::confide.alerts.wrong_credentials');
            }

            @if (! $restful)
            return Redirect::action('{{ $name }}@login')
            @else
            return Redirect::to('user/login')
            @endif
                ->withInput(request()->except('password'))
                ->with( 'error', $err_msg );
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param  string  $code
     */
    public function {{ (! $restful) ? 'confirm' : 'getConfirm' }}( $code )
    {
        if ( Confide::confirm( $code ) )
        {
            $notice_msg = __('confide::confide.alerts.confirmation');
            @if (! $restful)
            return Redirect::action('{{ $name }}@login')
            @else
            return Redirect::to('user/login')
            @endif
                ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = __('confide::confide.alerts.wrong_confirmation');
            @if (! $restful)
            return Redirect::action('{{ $name }}@login')
            @else
            return Redirect::to('user/login')
            @endif
                ->with( 'error', $error_msg );
        }
    }

    /**
     * Displays the forgot password form
     *
     */
    public function {{ (! $restful) ? 'forgot_password' : 'getForgot' }}()
    {
        return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     */
    public function {{ (! $restful) ? 'do_forgot_password' : 'postForgot' }}()
    {
        if( Confide::forgotPassword( request( 'email' ) ) )
        {
            $notice_msg = __('confide::confide.alerts.password_forgot');
            @if (! $restful)
            return Redirect::action('{{ $name }}@login')
            @else
            return Redirect::to('user/login')
            @endif
                ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = __('confide::confide.alerts.wrong_password_forgot');
            @if (! $restful)
            return Redirect::action('{{ $name }}@forgot_password')
            @else
            return Redirect::to('user/forgot')
            @endif
                ->withInput()
                ->with( 'error', $error_msg );
        }
    }

    /**
     * Shows the change password form with the given token
     *
     */
    public function {{ (! $restful) ? 'reset_password' : 'getReset' }}( $token )
    {
        return View::make(Config::get('confide::reset_password_form'))
                ->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     */
    public function {{ (! $restful) ? 'do_reset_password' : 'postReset' }}()
    {
        $input = array(
            'token'=>request( 'token' ),
            'password'=>request( 'password' ),
            'password_confirmation'=>request( 'password_confirmation' ),
        );

        // By passing an array with the token, password and confirmation
        if( Confide::resetPassword( $input ) )
        {
            $notice_msg = __('confide::confide.alerts.password_reset');
            @if (! $restful)
            return Redirect::action('{{ $name }}@login')
            @else
            return Redirect::to('user/login')
            @endif
                ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = __('confide::confide.alerts.wrong_password_reset');
            @if (! $restful)
            return Redirect::action('{{ $name }}@reset_password', array('token'=>$input['token']))
            @else
            return Redirect::to('user/reset/'.$input['token'])
            @endif
                ->withInput()
                ->with( 'error', $error_msg );
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function {{ (! $restful) ? 'logout' : 'getLogout' }}()
    {
        Confide::logout();

        return Redirect::to('/');
    }

}

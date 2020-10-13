<h1>{{ __('confide::confide.email.password_reset.subject') }}</h1>

<p>{{ __('confide::confide.email.password_reset.greetings', array( 'name' => $user->username)) }},</p>

<p>{{ __('confide::confide.email.password_reset.body') }}</p>
<a href='{{{ (Confide::checkAction('UserController@reset_password', array($token))) ? : URL::to('user/reset/'.$token)  }}}'>
    {{{ (Confide::checkAction('UserController@reset_password', array($token))) ? : URL::to('user/reset/'.$token)  }}}
</a>

<p>{{ __('confide::confide.email.password_reset.farewell') }}</p>

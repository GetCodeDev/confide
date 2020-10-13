<h1>{{ __('confide::confide.email.account_confirmation.subject') }}</h1>

<p>{{ __('confide::confide.email.account_confirmation.greetings', array( 'name' => $user->username)) }},</p>

<p>{{ __('confide::confide.email.account_confirmation.body') }}</p>
<a href='{{{ URL::to("user/confirm/{$user->confirmation_code}") }}}'>
    {{{ URL::to("user/confirm/{$user->confirmation_code}") }}}
</a>

<p>{{ __('confide::confide.email.account_confirmation.farewell') }}</p>

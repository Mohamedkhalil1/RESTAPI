@component('mail::message')
#Hello {{$user->name}}

thanks for register our website , to verify your account visit this link : 

@component('mail::button' ,['url' => route('verify',$user->verification_code)])
    Verify Account
@endcomponent

Thanks,<br>

@endcomponent


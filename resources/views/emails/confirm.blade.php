@component('mail::message')
#Hello {{$user->name}}

You changed your email , so you need to verify this new address. plesse use the link below: 

@component('mail::button' , ['url' => route('verify',$user->verification_code)])
    Verify Account
@endcomponent

Thanks,<br>
@endcomponent
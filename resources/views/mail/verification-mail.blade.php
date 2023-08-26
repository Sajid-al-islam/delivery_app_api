@component('mail::message')
# Verification

your login verification code is: <b>{{session()->get('verification')['code']}}</b>

Thanks,<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# Password recovery

Your temporary password is : <b>{{ $password }}</b><br>
For {{ config('app.name') }}.

Please Change it from the dashboard after login.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

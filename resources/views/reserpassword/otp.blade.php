@component('mail::message')
# Introduction

Reset Password OTP


@component('mail::panel')
Your OTP code is: {{$code}}
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent

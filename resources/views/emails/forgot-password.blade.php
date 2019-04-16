@extends('beautymail::templates.sunny')

@section('content')
@include('beautymail::templates.sunny.contentStart')
<h1 style="text-align: center;margin-bottom: 15px;margin-top: 25px;">Breathe: Forgot Password?</h1>
<p>
	Hi, 
</p>
<p>
	You recently requested to reset your password for your Breathe account. Use the button below to reset it. <b>This password reset is only valid for the next 24 hours.</b> 
</p>

<p style="text-align: center;"><a href="http://localhost:8000/reset-password/{{ $token }}" style="padding: 10px 45px;background: #00acac;color: white;font-size: 16px;margin-top: 37px;display: block;    margin-bottom: -35px;">Reset your password</a></p>

@include('beautymail::templates.sunny.contentEnd')
@stop
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Console</title>
    <!-- Fonts -->
    @include('inc.fonts')
    <link rel="stylesheet" href="{{ asset('vendors/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/select2/dist/css/select2.min.css') }}">
    {{--<link rel="stylesheet" href="{{ asset('vendors/jquery.mCustomScrollbar/jquery.mCustomScrollbar.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
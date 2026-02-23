<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>{{ config('app.name', 'Printing Coop') }}-Admin-@yield('title', 'Login')</title>
    @yield('before_head')
    <link href="{{ url('assets/admin/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/admin/css/login.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
@yield('before_body')
@yield('content')
</body>
</html>

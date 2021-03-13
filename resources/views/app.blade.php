<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Audio Converter</title>

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body>
  <div class="flex-center position-ref full-height" id="app">

    <router-view></router-view>
  </div>

  <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
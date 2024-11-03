<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon">
  <title>{{ config('app.name') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @stack('style')
</head>

<body>

  <x-navbar />

  <x-jumbotron />

  <main class="container mx-auto">
    {{ $slot }}
  </main>

  <script
    src="{{ !config('services.midtrans.isProduction') ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

  @stack('script')
</body>

</html>

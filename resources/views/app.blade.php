<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cafe Taraka</title>
    
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    
    {{-- Midtrans Snap.js --}}
    <script src="{{ config('services.midtrans.snap_url') }}" 
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
    @inertiaHead
  </head>
  <body class="bg-slate-50 text-slate-900 font-sans antialiased">
    @inertia
  </body>
</html>
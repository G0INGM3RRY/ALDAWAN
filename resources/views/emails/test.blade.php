@component('mail::message')
# {{ $greeting }}

@foreach ($introLines as $line)
{{ $line }}

@endforeach

@component('mail::button', ['url' => $actionUrl])
{{ $actionText }}
@endcomponent

@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{ $salutation }}
@endcomponent

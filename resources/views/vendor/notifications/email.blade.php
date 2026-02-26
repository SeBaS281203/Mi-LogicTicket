@component('mail::message')
# {{ $greeting ?? '¡Hola!' }}

@foreach ($introLines as $line)
{{ $line }}

@endforeach

@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => $level === 'error' ? 'error' : 'primary'])
{{ $actionText }}
@endcomponent
@endisset

@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Firma --}}
{{ $salutation ?? 'Saludos, el equipo de ChiclayoTicket' }}

{{-- Subcopy con la URL por si el botón falla --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "Si tienes problemas para hacer clic en el botón \":actionText\", copia y pega la siguiente URL en tu navegador:\n",
    ['actionText' => $actionText]
) <span class="break-all">[{{ $actionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset

@endcomponent


@component('mail::message')
# ¡Gracias por tu compra!

Aquí tienes tu clave de Steam:

@component('mail::panel')
{{ $key }}
@endcomponent

Disfrútalo,

{{ config('app.name') }}
@endcomponent

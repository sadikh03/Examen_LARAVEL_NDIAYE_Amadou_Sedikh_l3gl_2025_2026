@component('mail::message')
# Votre commande est prête !

Bonjour **{{ $commande->user->name }}**,

Votre commande **#{{ $commande->id }}** est **prête** et vous attend.

**Total à régler : {{ number_format($commande->total, 0, ',', ' ') }} FCFA**

Merci de votre confiance,
{{ config('app.name') }}
@endcomponent
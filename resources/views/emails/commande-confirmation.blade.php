@component('mail::message')
# Confirmation de commande

Bonjour **{{ $commande->user->name }}**,

Votre commande **#{{ $commande->id }}** a bien été reçue.

@component('mail::table')
| Burger | Qté | Prix unitaire | Sous-total |
|:-------|:---:|:-------------:|:----------:|
@foreach($commande->burgers as $burger)
| {{ $burger->nom }} | {{ $burger->pivot->quantite }} | {{ number_format($burger->pivot->prix_unitaire, 0, ',', ' ') }} FCFA | {{ number_format($burger->pivot->prix_unitaire * $burger->pivot->quantite, 0, ',', ' ') }} FCFA |
@endforeach
@endcomponent

**Total : {{ number_format($commande->total, 0, ',', ' ') }} FCFA**

Nous préparons votre commande dès que possible.

Merci de votre confiance,
{{ config('app.name') }}
@endcomponent
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; }

        .header { background-color: #ea580c; color: white; padding: 24px 30px; margin-bottom: 30px; }
        .header h1 { font-size: 26px; font-weight: bold; }
        .header p { font-size: 12px; opacity: 0.85; margin-top: 4px; }

        .section { padding: 0 30px; margin-bottom: 20px; }

        .info-grid { display: flex; justify-content: space-between; margin-bottom: 24px; padding: 0 30px; }
        .info-box h3 { font-size: 11px; text-transform: uppercase; color: #888; margin-bottom: 6px; }
        .info-box p { font-size: 13px; color: #333; line-height: 1.6; }

        table { width: 100%; border-collapse: collapse; margin: 0 30px; width: calc(100% - 60px); }
        thead { background-color: #fff7ed; }
        th { padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: #ea580c; border-bottom: 2px solid #fed7aa; }
        td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        tr:last-child td { border-bottom: none; }

        .total-row { background-color: #fff7ed; font-weight: bold; }
        .total-row td { padding: 12px; font-size: 15px; color: #ea580c; }

        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .badge-green { background-color: #dcfce7; color: #166534; }

        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #aaa; padding: 16px 30px; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>🍔 ISI BURGER</h1>
        <p>Facture officielle — Commande #{{ $commande->id }}</p>
    </div>

    {{-- INFOS --}}
    <div class="info-grid">
        <div class="info-box">
            <h3>Client</h3>
            <p>{{ $commande->user->name }}</p>
            <p>{{ $commande->user->email }}</p>
        </div>
        <div class="info-box">
            <h3>Commande</h3>
            <p>N° #{{ $commande->id }}</p>
            <p>Date : {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="info-box">
            <h3>Statut</h3>
            <span class="badge badge-green">{{ $commande->statut_label }}</span>
            @if($commande->paiement)
                <p style="margin-top:6px; font-size:12px; color:#555;">
                    Payée le {{ $commande->paiement->date_paiement->format('d/m/Y') }}
                </p>
            @endif
        </div>
    </div>

    {{-- TABLEAU ARTICLES --}}
    <table>
        <thead>
            <tr>
                <th>Burger</th>
                <th style="text-align:center">Qté</th>
                <th style="text-align:right">Prix unitaire</th>
                <th style="text-align:right">Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->burgers as $burger)
                <tr>
                    <td>{{ $burger->nom }}</td>
                    <td style="text-align:center">{{ $burger->pivot->quantite }}</td>
                    <td style="text-align:right">
                        {{ number_format($burger->pivot->prix_unitaire, 0, ',', ' ') }} FCFA
                    </td>
                    <td style="text-align:right">
                        {{ number_format($burger->pivot->prix_unitaire * $burger->pivot->quantite, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align:right">Total</td>
                <td style="text-align:right">
                    {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                </td>
            </tr>
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Merci pour votre confiance — ISI BURGER &bull;
        Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>
@extends('layouts.app')
@section('title', 'Statistiques')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-6">Tableau de bord</h1>

{{-- CARTES RÉSUMÉ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">

    <div class="bg-white rounded-2xl border shadow-sm p-5 flex items-center gap-4">
        <div class="bg-yellow-100 text-yellow-600 w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
            ⏳
        </div>
        <div>
            <p class="text-gray-500 text-sm">Commandes en cours</p>
            <p class="text-3xl font-bold text-gray-800">{{ $commandesEnCours }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border shadow-sm p-5 flex items-center gap-4">
        <div class="bg-green-100 text-green-600 w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
            ✅
        </div>
        <div>
            <p class="text-gray-500 text-sm">Commandes validées aujourd'hui</p>
            <p class="text-3xl font-bold text-gray-800">{{ $commandesValidees }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border shadow-sm p-5 flex items-center gap-4">
        <div class="bg-orange-100 text-orange-600 w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
            💰
        </div>
        <div>
            <p class="text-gray-500 text-sm">Recettes du jour</p>
            <p class="text-3xl font-bold text-orange-600">
                {{ number_format($recettesJour, 0, ',', ' ') }} FCFA
            </p>
        </div>
    </div>

</div>

{{-- GRAPHIQUES --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Commandes par mois --}}
    <div class="bg-white rounded-2xl border shadow-sm p-5">
        <h2 class="font-bold text-gray-700 mb-4">Commandes par mois</h2>
        <canvas id="chartCommandes" height="250"></canvas>
    </div>

    {{-- Stock des burgers --}}
    <div class="bg-white rounded-2xl border shadow-sm p-5">
        <h2 class="font-bold text-gray-700 mb-4">Stock des burgers (top 5 les plus bas)</h2>
        <canvas id="chartBurgers" height="250"></canvas>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Graphique commandes par mois
    new Chart(document.getElementById('chartCommandes'), {
        type: 'bar',
        data: {
            labels: @json($labelsCommandes),
            datasets: [{
                label: 'Commandes',
                data: @json($dataCommandes),
                backgroundColor: 'rgba(249, 115, 22, 0.7)',
                borderColor: 'rgba(249, 115, 22, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Graphique stock burgers
    new Chart(document.getElementById('chartBurgers'), {
        type: 'doughnut',
        data: {
            labels: @json($labelsBurgers),
            datasets: [{
                data: @json($dataBurgers),
                backgroundColor: [
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(234, 179, 8, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                ],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
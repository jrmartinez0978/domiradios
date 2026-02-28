@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-header')
    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
    <p class="text-gray-500 text-sm mt-1">Resumen general de Domiradios</p>
@endsection

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-admin.stat-card
            title="Total Radios"
            :value="$totalRadios"
            icon="fas fa-broadcast-tower"
            color="red"
        />
        <x-admin.stat-card
            title="Radios Activas"
            :value="$activeRadios"
            icon="fas fa-signal"
            color="green"
        />
        <x-admin.stat-card
            title="Blog Posts"
            :value="$totalBlogPosts"
            icon="fas fa-newspaper"
            color="blue"
        />
        <x-admin.stat-card
            title="Usuarios"
            :value="$totalUsers"
            icon="fas fa-users"
            color="amber"
        />
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Radios por Ciudad --}}
        <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Radios por Ciudad</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="chartByCity"></canvas>
            </div>
        </div>

        {{-- Radios por Género --}}
        <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Radios por Género</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="chartByGenre"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        {{-- Reproducciones Ultimos 7 Dias --}}
        <div class="bg-white rounded-2xl border border-surface-300 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Reproducciones Últimos 7 Días</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="chartPlays"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function initDashboardCharts() {
    if (typeof Chart === 'undefined') {
        setTimeout(initDashboardCharts, 100);
        return;
    }
    const chartColors = {
        primary: 'rgba(0, 80, 70, 0.8)',
        primaryBg: 'rgba(0, 80, 70, 0.2)',
        secondary: 'rgba(247, 107, 87, 0.8)',
        secondaryBg: 'rgba(247, 107, 87, 0.2)',
        gridColor: 'rgba(0, 0, 0, 0.06)',
        textColor: 'rgba(107, 114, 128, 1)',
    };

    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
        },
        scales: {
            x: {
                ticks: { color: chartColors.textColor, font: { size: 11 } },
                grid: { color: chartColors.gridColor },
                border: { color: chartColors.gridColor },
            },
            y: {
                ticks: { color: chartColors.textColor, font: { size: 11 } },
                grid: { color: chartColors.gridColor },
                border: { color: chartColors.gridColor },
                beginAtZero: true,
            },
        },
    };

    // Radios por Ciudad
    const cityData = @json($radiosByCity);
    new Chart(document.getElementById('chartByCity'), {
        type: 'bar',
        data: {
            labels: cityData.map(d => d.label),
            datasets: [{
                label: 'Radios',
                data: cityData.map(d => d.value),
                backgroundColor: chartColors.primary,
                borderColor: chartColors.primary,
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: defaultOptions,
    });

    // Radios por Género
    const genreData = @json($radiosByGenre);
    new Chart(document.getElementById('chartByGenre'), {
        type: 'bar',
        data: {
            labels: genreData.map(d => d.label),
            datasets: [{
                label: 'Radios',
                data: genreData.map(d => d.value),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 0.8)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: defaultOptions,
    });

    // Reproducciones Ultimos 7 Dias
    const playsData = @json($playsByDay);
    new Chart(document.getElementById('chartPlays'), {
        type: 'line',
        data: {
            labels: playsData.map(d => d.label),
            datasets: [{
                label: 'Reproducciones',
                data: playsData.map(d => d.value),
                borderColor: chartColors.secondary,
                backgroundColor: chartColors.secondaryBg,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: chartColors.secondary,
                pointBorderColor: chartColors.secondary,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: defaultOptions,
    });
}
document.addEventListener('DOMContentLoaded', initDashboardCharts);
</script>
@endpush

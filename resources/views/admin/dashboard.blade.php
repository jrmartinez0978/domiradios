@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-header')
    <h2 class="text-2xl font-bold text-gray-100">Dashboard</h2>
    <p class="text-dark-300 text-sm mt-1">Resumen general de Domiradios</p>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Radios por Ciudad --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-semibold text-gray-100 mb-4">Radios por Género</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="chartByCity"></canvas>
            </div>
        </div>

        {{-- Reproducciones Ultimos 7 Dias --}}
        <div class="glass-card p-6">
            <h3 class="text-lg font-semibold text-gray-100 mb-4">Reproducciones Ultimos 7 Dias</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="chartPlays"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartColors = {
        red: 'rgba(226, 28, 37, 0.8)',
        redBg: 'rgba(226, 28, 37, 0.2)',
        blue: 'rgba(59, 130, 246, 0.8)',
        blueBg: 'rgba(59, 130, 246, 0.2)',
        gridColor: 'rgba(255, 255, 255, 0.06)',
        textColor: 'rgba(139, 143, 168, 1)',
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
                backgroundColor: chartColors.red,
                borderColor: chartColors.red,
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
                borderColor: chartColors.blue,
                backgroundColor: chartColors.blueBg,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: chartColors.blue,
                pointBorderColor: chartColors.blue,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: defaultOptions,
    });
});
</script>
@endpush

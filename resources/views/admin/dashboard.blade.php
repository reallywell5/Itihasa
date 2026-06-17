@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex items-center justify-between overflow-hidden">
            <div>
                <p class="text-sm font-semibold text-blue-600 mb-2">
                    Dashboard Overview
                </p>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">
                    Welcome Back, Administrator
                </h1>
                <p class="text-sm text-slate-500 max-w-md">
                    Kelola data museum, tiket, transaksi, pembayaran, dan QR Code melalui panel admin.
                </p>

                <div class="mt-5">
                    <a href="{{ route('museums.index') }}"
                       class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition">
                        Kelola Museum
                    </a>
                </div>
            </div>

            <div class="hidden md:block">
                <div class="w-36 h-36 rounded-full bg-blue-50 flex items-center justify-center">
                    <svg class="w-20 h-20 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-100 mb-2">Total Revenue</p>
                    <h2 class="text-3xl font-bold">Rp 12.500K</h2>
                    <p class="text-sm mt-2 text-blue-100">+18% dari bulan lalu</p>
                </div>

                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        @php
            $cards = [
                ['title' => 'Museums', 'value' => '24', 'percent' => '+12%'],
                ['title' => 'Tickets', 'value' => '156', 'percent' => '+8%'],
                ['title' => 'Payments', 'value' => '89', 'percent' => '+15%'],
                ['title' => 'QR Codes', 'value' => '73', 'percent' => '+10%'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </div>

                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                        {{ $card['percent'] }}
                    </span>
                </div>

                <p class="text-sm text-slate-400 font-semibold">{{ $card['title'] }}</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $card['value'] }}</h3>
            </div>
        @endforeach

    </div>

    {{-- CHART & RECENT ACTIVITY --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Transaction Overview</h3>
                    <p class="text-sm text-slate-400">Data transaksi 6 bulan terakhir</p>
                </div>

                <select class="text-sm border border-blue-100 rounded-xl px-3 py-2 outline-none text-slate-500">
                    <option>2026</option>
                    <option>2025</option>
                </select>
            </div>

            <canvas id="activityChart" height="110"></canvas>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Recent Activity</h3>
            <p class="text-sm text-slate-400 mb-6">Aktivitas terbaru sistem</p>

            <div class="space-y-5">

                @php
                    $activities = [
                        ['icon' => '✓', 'title' => 'Payment Success', 'desc' => 'Pembayaran tiket berhasil diverifikasi.'],
                        ['icon' => '+', 'title' => 'New Museum Added', 'desc' => 'Admin menambahkan data museum baru.'],
                        ['icon' => '!', 'title' => 'Pending Transaction', 'desc' => 'Ada transaksi yang menunggu konfirmasi.'],
                        ['icon' => 'QR', 'title' => 'QR Code Generated', 'desc' => 'QR Code tiket berhasil dibuat.'],
                    ];
                @endphp

                @foreach ($activities as $activity)
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 font-bold text-sm">
                            {{ $activity['icon'] }}
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-700">{{ $activity['title'] }}</h4>
                            <p class="text-xs text-slate-400">{{ $activity['desc'] }}</p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('activityChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [{
            label: 'Transactions',
            data: [12, 19, 14, 25, 22, 30],
            backgroundColor: '#2563eb',
            hoverBackgroundColor: '#1d4ed8',
            borderRadius: 12,
            barThickness: 35
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
                grid: { color: '#dbeafe' },
                ticks: { color: '#64748b' }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#64748b' }
            }
        }
    }
});
</script>
@endpush

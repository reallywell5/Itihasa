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
                    Welcome Back, {{ auth()->user()->name }}
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

        </div>

        {{-- REVENUE --}}
        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">Total Revenue</p>

            <h2 class="text-3xl font-bold">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </h2>

            <p class="text-sm mt-2 text-blue-100">
                Total seluruh transaksi berhasil
            </p>
        </div>

    </div>

    {{-- STATISTICS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold">Museums</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-2">
                {{ $totalMuseums }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold">Tickets</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-2">
                {{ $totalTickets }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold">Payments</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-2">
                {{ $totalPayments }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold">QR Codes</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-2">
                {{ $totalQrCodes }}
            </h3>
        </div>

    </div>

    {{-- CHART + ACTIVITY --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- CHART --}}
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-blue-100 p-6">

            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800">
                    Transaction Overview
                </h3>

                <p class="text-sm text-slate-400">
                    Data transaksi 6 bulan terakhir
                </p>
            </div>

            <canvas id="activityChart" height="110"></canvas>

        </div>

        {{-- RECENT ACTIVITY --}}
        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">

            <h3 class="text-lg font-bold text-slate-800 mb-1">
                Recent Activity
            </h3>

            <p class="text-sm text-slate-400 mb-6">
                Aktivitas transaksi terbaru
            </p>

            <div class="space-y-5">

                @forelse($recentTransactions as $transaction)

                    <div class="flex gap-4">

                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                            TR
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-slate-700">
                                {{ $transaction->user->name ?? 'Guest' }}
                            </h4>

                            <p class="text-xs text-slate-400">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </p>
                        </div>

                    </div>

                @empty

                    <p class="text-sm text-slate-400">
                        Belum ada aktivitas terbaru.
                    </p>

                @endforelse

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
        labels: {!! json_encode($monthlyTransactions['labels']) !!},
        datasets: [{
            label: 'Transactions',
            data: {!! json_encode($monthlyTransactions['data']) !!},
            backgroundColor: '#2563eb',
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
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush

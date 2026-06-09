@extends('layouts.app')

@section('title', 'Manajemen Pengunjung')

@section('content')
<div class="space-y-6 text-slate-800 font-sans antialiased">

    <!-- Header Utama - Tema Kontrol Akses (Indigo Accent) -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Otoritas Pengguna & Hak Akses
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Akun Pengunjung</h1>
            <p class="text-slate-500 text-sm mt-0.5">Kelola verifikasi data identitas, klasifikasi tingkat peran, dan riwayat pendaftaran pengguna.</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-lg text-sm transition-all shadow-sm tracking-wide shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengguna
        </a>
    </div>

    <!-- Informasi Notifikasi Sistem -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center justify-between shadow-xs animate-fade-in">
        <div class="flex items-center gap-3">
            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Tabel Database Pengguna -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">

        <!-- Identifikasi Konten Bar -->
        <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Database Akun Pengguna</span>
            <span class="text-xs font-medium text-slate-500 bg-slate-200/60 px-2.5 py-1 rounded-md font-mono">TOTAL: {{ method_exists($users, 'total') ? $users->total() : count($users) }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/40 text-slate-500 text-xs tracking-wider font-bold uppercase">
                        <th class="py-4 px-6 w-[35%]">Profil Pengguna</th>
                        <th class="py-4 px-6 w-[30%]">Alamat Email</th>
                        <th class="py-4 px-6 w-[15%]">Peran Sistem</th>
                        <th class="py-4 px-6 w-[15%]">Tanggal Gabung</th>
                        <th class="py-4 px-6 w-[10%] text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($users as $user)
                    <tr class="hover:bg-slate-50/40 transition-colors duration-150 group">

                        <!-- Informasi Profil dengan Avatar Inisial -->
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100/80 text-indigo-700 font-bold text-sm flex items-center justify-center tracking-wider shrink-0 group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all uppercase">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block text-[15px] tracking-tight leading-tight">{{ $user->name }}</span>
                                    <span class="text-xs text-slate-400 block mt-0.5">ID: USR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Informasi Kontak Email -->
                        <td class="py-4 px-6 text-slate-600 font-normal text-[14px]">
                            {{ $user->email }}
                        </td>

                        <!-- Desain Badge Tingkat Peran (Role) -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider">
                                    Admin
                                </span>
                            @elseif($user->role === 'petugas')
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wider">
                                    Petugas
                                </span>
                            @elseif($user->role === 'user')
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                                    User
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-bold bg-stone-100 text-stone-500 border border-stone-200 uppercase tracking-wider">
                                    Belum Diatur
                                </span>
                            @endif
                        </td>

                        <!-- Tanggal Registrasi -->
                        <td class="py-4 px-6 text-slate-500 text-[13.5px] font-medium">
                            {{ $user->created_at?->format('d M Y') ?? '-' }}
                        </td>

                        <!-- Tombol Aksi Kontrol Akses -->
                        <td class="py-4 px-6 text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 text-slate-400 hover:text-slate-800 hover:bg-slate-100 border border-transparent hover:border-slate-200 rounded-lg transition-all" title="Ubah Parameter">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini secara permanen dari sistem?')" class="inline-flex items-center justify-center w-8 h-8 text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 rounded-lg transition-all" title="Eliminasi Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center text-slate-400">
                            <div class="text-slate-300 flex justify-center mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">Belum Ada Pengguna</h3>
                            <p class="text-xs text-slate-400 mt-1">Sistem belum mendeteksi adanya data akun terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Navigasi Halaman (Pagination) -->
    @if(method_exists($users, 'links'))
    <div class="pt-2">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

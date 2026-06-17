<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Itihasa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-[#f4efe7]">

<div class="min-h-screen flex items-center justify-center px-6 py-10">

    <div class="w-full max-w-5xl bg-[#fffaf3] rounded-[32px] shadow-2xl border border-[#e6d8c3] overflow-hidden grid grid-cols-1 lg:grid-cols-2">

        {{-- LEFT --}}
        <div class="relative bg-[#102a43] text-white p-10 lg:p-12 overflow-hidden">

            <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full border-[38px] border-[#d4af37]/20"></div>
            <div class="absolute -bottom-20 -right-20 w-64 h-64 rounded-full border-[34px] border-[#d4af37]/20"></div>
            <div class="absolute top-1/2 left-1/2 w-80 h-80 rounded-full border border-white/10 -translate-x-1/2 -translate-y-1/2"></div>

            <div class="relative z-10 h-full flex flex-col justify-between min-h-[520px]">

                <div>

                    {{-- BRAND --}}
                    <div class="flex items-center gap-5 mb-12">
                        <div class="w-24 h-24 rounded-3xl bg-[#fffaf3] flex items-center justify-center shadow-xl">
                            <img src="{{ asset('images/logo-itihasa.png') }}"
                                 alt="Itihasa Logo"
                                 class="w-20 h-20 object-contain">
                        </div>

                        <div>
                            <h1 class="text-4xl font-serif tracking-[0.22em] text-white">
                                ITIHASA
                            </h1>

                            <p class="mt-2 text-[#d4af37] text-xs font-bold tracking-[0.25em] uppercase">
                                Cultural Heritage
                            </p>
                        </div>
                    </div>

                    {{-- TEXT --}}
                    <div class="space-y-5 max-w-md">
                        <h2 class="text-4xl font-serif leading-tight text-white">
                            Mulailah perjalanan budaya dan sejarah bersama Itihasa.
                        </h2>

                        <p class="text-slate-300 leading-relaxed text-base">
                            Buat akun untuk mengakses layanan museum digital, pemesanan tiket, QR Code, dan pengalaman wisata budaya yang lebih modern.
                        </p>
                    </div>

                </div>

                <div class="border-t border-white/15 pt-6">
                    <p class="text-sm text-slate-300 italic leading-relaxed">
                        “Setiap artefak memiliki cerita, dan setiap cerita layak untuk dikenang.”
                    </p>
                </div>

            </div>
        </div>

        {{-- RIGHT --}}
        <div class="p-8 lg:p-12 flex items-center bg-[#fffaf3]">

            <div class="w-full max-w-md mx-auto">

                <div class="mb-8">
                    <p class="text-sm font-bold text-[#b88a2a] uppercase tracking-widest mb-3">
                        Create Account
                    </p>

                    <h2 class="text-4xl font-black text-[#102a43]">
                        Daftar Akun
                    </h2>

                    <p class="text-slate-500 mt-3">
                        Buat akun baru untuk mulai menggunakan sistem Itihasa.
                    </p>
                </div>

                <form method="POST" action="{{ route('register.process') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-[#102a43] mb-2">
                            Nama Lengkap
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Masukkan nama lengkap"
                               required
                               class="w-full px-5 py-4 rounded-2xl bg-white border border-[#e2d2b8] focus:outline-none focus:ring-2 focus:ring-[#d4af37] text-[#102a43]">

                        @error('name')
                            <p class="text-sm text-[#b88a2a] mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-[#102a43] mb-2">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               required
                               class="w-full px-5 py-4 rounded-2xl bg-white border border-[#e2d2b8] focus:outline-none focus:ring-2 focus:ring-[#d4af37] text-[#102a43]">

                        @error('email')
                            <p class="text-sm text-[#b88a2a] mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-[#102a43] mb-2">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               placeholder="Minimal 8 karakter"
                               required
                               class="w-full px-5 py-4 rounded-2xl bg-white border border-[#e2d2b8] focus:outline-none focus:ring-2 focus:ring-[#d4af37] text-[#102a43]">

                        @error('password')
                            <p class="text-sm text-[#b88a2a] mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-[#102a43] mb-2">
                            Konfirmasi Password
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               placeholder="Ulangi password"
                               required
                               class="w-full px-5 py-4 rounded-2xl bg-white border border-[#e2d2b8] focus:outline-none focus:ring-2 focus:ring-[#d4af37] text-[#102a43]">
                    </div>

                    <button type="submit"
                            class="w-full py-4 rounded-2xl bg-[#102a43] text-white font-bold hover:bg-[#0b1f33] transition shadow-lg">
                        Daftar Sekarang
                    </button>
                </form>

                <p class="text-center text-sm text-slate-500 mt-7">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-[#b88a2a] font-black">
                        Login
                    </a>
                </p>

            </div>
        </div>

    </div>

</div>

</body>
</html>

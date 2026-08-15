<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - Itihasa Heritage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cinzel:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-cinzel { font-family: 'Cinzel', serif; }
    </style>
</head>

<body class="min-h-screen bg-[#F4EFE7] flex items-center justify-center p-3 sm:p-6 lg:p-10">

    <div class="w-full max-w-4xl bg-[#FFFAF3] rounded-3xl sm:rounded-[32px] shadow-2xl border border-[#E6D8C3] overflow-hidden grid grid-cols-1 lg:grid-cols-2">

        {{-- LEFT BRANDING PANEL --}}
        <div class="relative bg-[#102A43] text-white p-6 sm:p-8 lg:p-10 overflow-hidden flex flex-col justify-between">
            <div class="absolute -top-20 -left-20 w-60 h-60 rounded-full border-[30px] border-[#D4AF37]/15"></div>
            <div class="absolute -bottom-16 -right-16 w-52 h-52 rounded-full border-[26px] border-[#D4AF37]/15"></div>

            <div class="relative z-10 space-y-6">
                {{-- BRAND --}}
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#FFFAF3] p-1.5 flex items-center justify-center shadow-lg shrink-0">
                        <img src="{{ asset('images/logo-itihasa.png') }}"
                             alt="Itihasa Logo"
                             class="w-full h-full object-contain">
                    </div>

                    <div>
                        <h1 class="text-xl sm:text-2xl font-cinzel font-bold tracking-[0.2em] text-white">
                            ITIHASA
                        </h1>
                        <p class="text-[#D4AF37] text-[10px] font-bold tracking-[0.2em] uppercase">
                            Cultural Heritage
                        </p>
                    </div>
                </div>

                {{-- TAGLINE --}}
                <div class="space-y-2 hidden sm:block">
                    <h2 class="text-xl sm:text-2xl font-bold font-cinzel text-white leading-snug">
                        Mulailah Petualangan Budaya Bersama Itihasa
                    </h2>
                    <p class="text-slate-300 text-xs leading-relaxed">
                        Buat akun untuk memesan tiket secara instan, simpan museum favorit, dan nikmati kemudahan akses digital.
                    </p>
                </div>
            </div>

            <div class="relative z-10 pt-4 border-t border-white/10 mt-6 hidden sm:block">
                <p class="text-[11px] text-slate-300 italic">
                    “Setiap artefak memiliki cerita, dan setiap cerita layak untuk dikenang.”
                </p>
            </div>
        </div>

        {{-- RIGHT FORM PANEL --}}
        <div class="p-6 sm:p-8 lg:p-10 flex items-center bg-[#FFFAF3]">
            <div class="w-full max-w-sm mx-auto">

                <div class="mb-5">
                    <span class="text-[10px] font-bold text-[#B88A2A] uppercase tracking-widest block mb-1">
                        Pendaftaran Baru
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#102A43]">
                        Daftar Akun
                    </h2>
                    <p class="text-slate-500 text-xs mt-1">
                        Lengkapi data diri untuk memulai penjelajahan museum.
                    </p>
                </div>

                <form method="POST" action="{{ route('register.process') }}" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-[#102A43] mb-1">
                            Nama Lengkap
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Nama lengkap Anda"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E2D2B8] text-xs text-[#102A43] focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#102A43] mb-1">
                            Alamat Email
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E2D2B8] text-xs text-[#102A43] focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#102A43] mb-1">
                            Kata Sandi
                        </label>
                        <input type="password"
                               name="password"
                               placeholder="Minimal 8 karakter"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E2D2B8] text-xs text-[#102A43] focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#102A43] mb-1">
                            Konfirmasi Sandi
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               placeholder="Ulangi kata sandi"
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E2D2B8] text-xs text-[#102A43] focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-xl bg-[#102A43] text-white font-bold text-xs hover:bg-[#0B1F33] transition shadow-md mt-2">
                        Daftar Sekarang
                    </button>
                </form>

                <div class="mt-5 pt-4 border-t border-[#E6D8C3]/60 flex items-center justify-between text-xs">
                    <a href="{{ route('user.home') }}" class="text-slate-500 hover:text-[#102A43] font-medium flex items-center gap-1">
                        ← Beranda
                    </a>
                    <p class="text-slate-500">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-[#B88A2A] font-bold hover:underline">
                            Login
                        </a>
                    </p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>

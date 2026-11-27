<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun — Museum Itihasa Nusantara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            color: #1A1A1A;
            background-color: #F8F7F4;
        }
        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[#F8F7F4] flex items-center justify-center p-4 sm:p-6 lg:p-10 antialiased selection:bg-[#1A1A1A] selection:text-[#F8F7F4]">

    <div class="w-full max-w-4xl bg-white rounded-sm border border-neutral-200 overflow-hidden grid grid-cols-1 lg:grid-cols-12">

        {{-- LEFT INSTITUTIONAL BRANDING PANEL --}}
        <div class="lg:col-span-5 relative bg-[#121212] text-white p-7 sm:p-10 flex flex-col justify-between overflow-hidden">
            <img src="{{ asset('images/institutional/hero-museum.jpg') }}"
                 alt="Museum Itihasa"
                 class="absolute inset-0 w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-[#121212] via-[#121212]/70 to-[#121212]/80"></div>

            <div class="relative z-10 space-y-6">
                {{-- BRAND --}}
                <a href="{{ route('user.home') }}" class="flex items-center gap-3 group w-fit">
                    <div class="w-11 h-11 rounded-sm bg-white p-1.5 flex items-center justify-center border border-neutral-200 shrink-0">
                        <img src="{{ asset('images/logo-itihasa.png') }}"
                             alt="Itihasa Logo"
                             class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="font-serif text-xl font-semibold tracking-tight text-white uppercase block leading-none">
                            ITIHASA
                        </span>
                        <span class="text-[#C59B27] text-[10px] font-medium tracking-[0.22em] uppercase block mt-1">
                            Museum Nusantara
                        </span>
                    </div>
                </a>

                {{-- TAGLINE --}}
                <div class="space-y-2.5 pt-2 hidden sm:block">
                    <p class="text-[10px] uppercase tracking-[0.22em] text-[#C59B27] font-semibold">
                        Akses Pengunjung
                    </p>
                    <h2 class="font-serif text-2xl font-medium text-white leading-snug tracking-tight">
                        Jelajahi Sejarah &amp; Warisan Budaya Nusantara
                    </h2>
                    <p class="text-neutral-300 text-xs leading-relaxed">
                        Portal reservasi tiket resmi, paspor budaya digital, dan validasi kunjungan museum terintegrasi.
                    </p>
                </div>
            </div>

            <div class="relative z-10 pt-5 border-t border-white/15 mt-8 hidden sm:block">
                <p class="font-serif italic text-xs text-neutral-300 leading-relaxed">
                    “Budaya adalah cerita yang tetap hidup ketika kepedulian dan teknologi menjaganya.”
                </p>
            </div>
        </div>

        {{-- RIGHT FORM PANEL --}}
        <div class="lg:col-span-7 p-7 sm:p-10 lg:p-12 flex items-center bg-white">
            <div class="w-full max-w-sm mx-auto">

                <div class="mb-7">
                    <span class="text-[11px] font-semibold text-[#B8860B] uppercase tracking-[0.2em] block mb-1.5">
                        Selamat Datang Kembali
                    </span>
                    <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[#1A1A1A] tracking-tight">
                        Masuk Akun
                    </h1>
                    <p class="text-[#5A5A5A] text-xs sm:text-sm mt-1.5">
                        Masuk untuk mengelola reservasi tiket dan koleksi favorit Anda.
                    </p>
                </div>

                @if(session('success'))
                    <div class="mb-5 p-3.5 rounded-sm bg-emerald-50 text-emerald-900 text-xs font-medium border border-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 p-3.5 rounded-sm bg-rose-50 text-[#8C3B2B] text-xs font-medium border border-rose-200">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                            Alamat Email
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               required
                               class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition duration-200">
                        @error('email')
                            <p class="text-xs text-[#8C3B2B] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                            Kata Sandi
                        </label>
                        <input type="password"
                               name="password"
                               placeholder="Masukkan kata sandi"
                               required
                               class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition duration-200">
                        @error('password')
                            <p class="text-xs text-[#8C3B2B] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-neutral-200 flex items-center justify-between text-xs">
                    <a href="{{ route('user.home') }}" class="text-[#5A5A5A] hover:text-[#1A1A1A] font-medium flex items-center gap-1 transition duration-200">
                        ← Beranda
                    </a>
                    <p class="text-[#5A5A5A]">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-[#8C3B2B] font-semibold hover:underline">
                            Daftar
                        </a>
                    </p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>

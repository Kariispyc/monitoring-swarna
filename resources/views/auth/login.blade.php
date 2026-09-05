<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Operator - SWARNA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-slate-200 p-8 space-y-6">
        
        <!-- Header Logo -->
        <div class="text-center space-y-2">
            <img src="{{ asset('images/logo-swarna.jpg') }}" alt="Logo SWARNA" class="h-16 w-16 mx-auto rounded-full bg-emerald-100 p-1 object-contain shadow-sm" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=SWARNA&background=10b981&color=fff';">
            <h2 class="text-2xl font-extrabold text-slate-800">Login Operator</h2>
            <p class="text-xs text-slate-500">Masukkan akun operator untuk mengakses kontrol sakelar & rekap data SWARNA</p>
        </div>

        <!-- Pesan Error -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-xs rounded-lg p-3">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            
<div>
    <label for="email" class="block text-xs font-bold uppercase text-slate-600 mb-1">Email Operator</label>
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">
            <i class="fa-solid fa-envelope"></i>
        </span>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="swarna@warnasari.id" class="w-full pl-9 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
    </div>
</div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase text-slate-600 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password" class="w-full pl-9 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg text-sm shadow-md transition">
                Masuk Sistem
            </button>
        </form>

        <div class="text-center pt-2">
            <a href="{{ route('dashboard') }}" class="text-xs text-emerald-600 hover:underline font-semibold">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Dashboard Publik
            </a>
        </div>
    </div>

</body>
</html>
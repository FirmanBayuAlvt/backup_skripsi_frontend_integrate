<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TernakPark</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .login-gradient {
            background: linear-gradient(135deg, #0f4c3a 0%, #1e7b5e 100%);
        }
    </style>
</head>
<body class="login-gradient min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo TernakPark Wonosalam.png') }}" alt="TernakPark" class="h-16 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang</h1>
            <p class="text-gray-500 mt-1">Silakan masuk ke akun Anda</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" required
                           class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="admin@ternakpark.com">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" required
                           class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="••••••••">
                </div>
            </div>
            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition duration-300 transform hover:scale-[1.02] shadow-lg">
                Masuk
            </button>
        </form>

        @if($errors->any())
            <div class="mt-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} TernakPark. All rights reserved.
        </p>
    </div>
</body>
</html>

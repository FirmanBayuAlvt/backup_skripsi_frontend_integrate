<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-green-400 to-green-700 min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <img src="<?php echo e(asset('images/logo-ternakpark.png')); ?>" alt="TernakPark" class="h-24 mx-auto mb-6">
        <h1 class="text-9xl font-bold text-white">404</h1>
        <p class="text-2xl text-white mt-4">Halaman Tidak Ditemukan</p>
        <a href="<?php echo e(url('/')); ?>" class="mt-8 inline-block px-6 py-3 bg-white text-green-700 font-semibold rounded-lg hover:bg-green-50 transition transform hover:scale-105">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
<?php /**PATH D:\Tugas Akhir\Aplikasi_TernakParkWonosalam\ternakpark-frontend\resources\views/errors/404.blade.php ENDPATH**/ ?>
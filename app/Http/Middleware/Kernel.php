protected $routeMiddleware = [
    // ... middleware lainnya
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'petugas' => \App\Http\Middleware\PetugasMiddleware::class,
];
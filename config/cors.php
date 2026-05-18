<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Permite que VistaAdmin (8001) y HttpClient (8002) hagan peticiones HTTP
    | al Core (8000) sin ser bloqueadas por el navegador o por PHP.
    |
    */

    // ✅ Aplicar CORS a todas las rutas de la API
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // ✅ En desarrollo permite todos los orígenes. En producción, lista las URLs de Railway.
    'allowed_origins' => ['*'],

    // Alternativa para producción (descomenta y ajusta las URLs):
    // 'allowed_origins' => [
    //     'http://localhost:8001',
    //     'http://localhost:8002',
    //     'http://127.0.0.1:8001',
    //     'http://127.0.0.1:8002',
    //     'https://tu-admin.railway.app',
    //     'https://tu-cliente.railway.app',
    // ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // ✅ Necesario para que Sanctum envíe cookies con credenciales
    'supports_credentials' => false,

];

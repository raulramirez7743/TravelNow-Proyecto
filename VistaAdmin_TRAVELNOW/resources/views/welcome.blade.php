<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>HttpClient TravelNow — Panel de Control</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;700;800&family=DM+Mono:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
<style>
  :root {
    --sky: #0ea5e9; --sky-dark: #0369a1; --sky-light: #e0f2fe;
    --navy: #0f172a; --navy-mid: #1e293b; --navy-soft: #334155;
    --gold: #f59e0b; --gold-light: #fef3c7;
    --coral: #f43f5e; --coral-light: #ffe4e6;
    --mint: #10b981; --mint-light: #d1fae5;
    --purple: #8b5cf6; --purple-light: #ede9fe;
    --slate: #64748b; --slate-light: #f1f5f9;
    --white: #ffffff;
    --text: #0f172a; --text-muted: #64748b;
    --border: rgba(15,23,42,0.08);
    --radius: 14px; --radius-sm: 8px;
    --shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.06);
    --shadow-lg: 0 8px 32px rgba(0,0,0,0.12);
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'DM Sans', sans-serif; background: #f8fafc; color: var(--text); min-height: 100vh; }

  /* SIDEBAR */
  .sidebar {
    position: fixed; top: 0; left: 0; height: 100vh; width: 240px;
    background: var(--navy); display: flex; flex-direction: column; z-index: 100; overflow: hidden;
  }
  .sidebar-logo {
    padding: 28px 24px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
  }
  .sidebar-logo .brand { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 20px; color: white; letter-spacing: -0.5px; }
  .sidebar-logo .sub { font-size: 11px; color: rgba(255,255,255,0.35); letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
  .sidebar-label { font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.25); padding: 20px 24px 8px; }
  .nav-item {
    display: flex; align-items: center; gap: 12px; padding: 10px 20px 10px 24px;
    cursor: pointer; color: rgba(255,255,255,0.55); font-size: 13.5px; font-weight: 400;
    transition: all 0.15s; border-left: 3px solid transparent; margin: 1px 0;
  }
  .nav-item:hover { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.9); }
  .nav-item.active { background: rgba(14,165,233,0.12); color: var(--sky); border-left-color: var(--sky); }
  .nav-item .nav-icon { width: 18px; text-align: center; font-size: 15px; }
  .sidebar-footer { margin-top: auto; padding: 16px 24px; border-top: 1px solid rgba(255,255,255,0.06); }
  .api-badge { background: rgba(14,165,233,0.15); border: 1px solid rgba(14,165,233,0.25); border-radius: 6px; padding: 8px 12px; }
  .api-badge .label { font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--sky); }
  .api-badge .url { font-family: 'DM Mono', monospace; font-size: 10px; color: rgba(255,255,255,0.5); margin-top: 2px; }

  /* MAIN */
  .main { margin-left: 240px; min-height: 100vh; }
  .topbar {
    background: white; border-bottom: 1px solid var(--border); padding: 0 32px;
    height: 64px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50;
  }
  .page-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 20px; color: var(--navy); }
  .topbar-actions { display: flex; align-items: center; gap: 12px; }
  .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; cursor: pointer; border: none; transition: all 0.15s; font-family: 'DM Sans', sans-serif; }
  .btn-primary { background: var(--sky); color: white; }
  .btn-primary:hover { background: var(--sky-dark); }
  .btn-danger { background: var(--coral-light); color: var(--coral); }
  .btn-danger:hover { background: var(--coral); color: white; }
  .btn-ghost { background: var(--slate-light); color: var(--slate); }
  .btn-ghost:hover { background: #e2e8f0; }
  .btn-sm { padding: 5px 10px; font-size: 12px; }

  /* CONTENT */
  .content { padding: 28px 32px; }
  .section { display: none; }
  .section.active { display: block; }

  /* STATS ROW */
  .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 28px; }
  .stat-card { background: white; border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); }
  .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; margin-bottom: 12px; }
  .stat-value { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1; }
  .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

  /* CARD */
  .card { background: white; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
  .card-header { padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
  .card-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: var(--navy); }
  .card-body { padding: 0; }

  /* TABLE */
  .tbl-wrap { overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
  thead th { padding: 11px 16px; text-align: left; font-size: 10.5px; letter-spacing: 0.8px; text-transform: uppercase; color: var(--text-muted); font-weight: 500; background: #f8fafc; border-bottom: 1px solid var(--border); }
  tbody tr { border-bottom: 1px solid var(--border); transition: background 0.1s; }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: #fafbfd; }
  td { padding: 12px 16px; color: var(--text); vertical-align: middle; }
  .badge { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 500; }
  .badge-sky { background: var(--sky-light); color: var(--sky-dark); }
  .badge-gold { background: var(--gold-light); color: #92400e; }
  .badge-mint { background: var(--mint-light); color: #065f46; }
  .badge-coral { background: var(--coral-light); color: #9f1239; }
  .badge-purple { background: var(--purple-light); color: #5b21b6; }

  /* EMPTY STATE */
  .empty { text-align: center; padding: 60px 20px; color: var(--text-muted); }
  .empty .empty-icon { font-size: 40px; margin-bottom: 12px; opacity: 0.4; }
  .empty p { font-size: 14px; }

  /* MODAL */
  .modal-overlay {
    position: fixed; inset: 0; background: rgba(15,23,42,0.55); z-index: 1000;
    display: flex; align-items: center; justify-content: center; opacity: 0;
    pointer-events: none; transition: opacity 0.2s; backdrop-filter: blur(4px);
  }
  .modal-overlay.open { opacity: 1; pointer-events: all; }
  .modal { background: white; border-radius: 20px; width: 480px; max-width: 96vw; box-shadow: var(--shadow-lg); transform: translateY(12px); transition: transform 0.25s; max-height: 90vh; overflow-y: auto; }
  .modal-overlay.open .modal { transform: translateY(0); }
  .modal-head { padding: 24px 28px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
  .modal-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 17px; color: var(--navy); }
  .modal-close { width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--border); background: none; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; color: var(--slate); transition: all 0.15s; }
  .modal-close:hover { background: var(--slate-light); }
  .modal-body { padding: 24px 28px; }
  .modal-foot { padding: 16px 28px; border-top: 1px solid var(--border); display: flex; gap: 8px; justify-content: flex-end; }

  /* FORM */
  .form-group { margin-bottom: 16px; }
  .form-label { font-size: 12px; font-weight: 500; color: var(--navy); display: block; margin-bottom: 6px; letter-spacing: 0.3px; }
  .form-control { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: var(--radius-sm); font-size: 13.5px; font-family: 'DM Sans', sans-serif; color: var(--text); transition: border 0.15s; outline: none; background: white; }
  .form-control:focus { border-color: var(--sky); box-shadow: 0 0 0 3px rgba(14,165,233,0.1); }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

  /* TOAST */
  .toaster { position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
  .toast { padding: 12px 18px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; box-shadow: var(--shadow-lg); animation: toastIn 0.25s ease; display: flex; align-items: center; gap: 8px; max-width: 320px; }
  .toast-success { background: var(--mint-light); color: #065f46; border: 1px solid #6ee7b7; }
  .toast-error { background: var(--coral-light); color: #9f1239; border: 1px solid #fca5a5; }
  @keyframes toastIn { from { transform: translateX(40px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

  /* LOADER */
  .spinner { width: 20px; height: 20px; border: 2px solid rgba(14,165,233,0.2); border-top-color: var(--sky); border-radius: 50%; animation: spin 0.7s linear infinite; display: inline-block; }
  @keyframes spin { to { transform: rotate(360deg); } }
  .loading-row td { text-align: center; padding: 40px; color: var(--text-muted); }

  /* CODE HIGHLIGHT */
  .code-block { background: var(--navy); border-radius: var(--radius-sm); padding: 16px; margin-top: 8px; overflow-x: auto; }
  .code-block pre { font-family: 'DM Mono', monospace; font-size: 11.5px; color: #94a3b8; line-height: 1.7; }
  .code-block .kw { color: #7dd3fc; }
  .code-block .fn { color: #86efac; }
  .code-block .str { color: #fde68a; }
  .code-block .cm { color: #475569; }
  .code-block .var { color: #f9a8d4; }

  /* CODE PANEL */
  .code-panel { background: var(--navy-mid); border-radius: var(--radius); margin-bottom: 28px; overflow: hidden; }
  .code-panel-header { display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: rgba(255,255,255,0.04); border-bottom: 1px solid rgba(255,255,255,0.06); }
  .code-panel-title { font-family: 'DM Mono', monospace; font-size: 11px; color: rgba(255,255,255,0.4); }
  .dot { width: 10px; height: 10px; border-radius: 50%; }

  /* HOME WELCOME */
  .welcome-hero { background: linear-gradient(135deg, var(--navy) 0%, var(--navy-soft) 100%); border-radius: var(--radius); padding: 40px; color: white; margin-bottom: 28px; position: relative; overflow: hidden; }
  .welcome-hero::after { content: '✈'; position: absolute; right: 40px; top: 50%; transform: translateY(-50%); font-size: 120px; opacity: 0.05; }
  .welcome-hero h1 { font-family: 'Syne', sans-serif; font-size: 30px; font-weight: 800; margin-bottom: 8px; }
  .welcome-hero p { color: rgba(255,255,255,0.6); font-size: 14px; max-width: 500px; }
  .tag { display: inline-flex; align-items: center; gap: 4px; background: rgba(14,165,233,0.2); border: 1px solid rgba(14,165,233,0.3); color: var(--sky); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 500; margin-bottom: 16px; }

  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  @media (max-width: 800px) { .grid-2 { grid-template-columns: 1fr; } }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="brand">TravelNow</div>
    <div class="sub">HttpClient Panel</div>
  </div>
  <div class="sidebar-label">Inicio</div>
  <div class="nav-item active" onclick="showSection('home', this)">
    <span class="nav-icon">⬡</span> Dashboard
  </div>
  <div class="sidebar-label">Tablas CRUD</div>
  <div class="nav-item" onclick="showSection('destinos', this)">
    <span class="nav-icon">🌍</span> Destinos
  </div>
  <div class="nav-item" onclick="showSection('hoteles', this)">
    <span class="nav-icon">🏨</span> Hoteles
  </div>
  <div class="nav-item" onclick="showSection('habitaciones', this)">
    <span class="nav-icon">🛏</span> Habitaciones
  </div>
  <div class="nav-item" onclick="showSection('vuelos', this)">
    <span class="nav-icon">✈</span> Vuelos
  </div>
  <div class="nav-item" onclick="showSection('usuarios', this)">
    <span class="nav-icon">👤</span> Usuarios Admin
  </div>
  <div class="nav-item" onclick="showSection('clientes', this)">
    <span class="nav-icon">👥</span> Clientes Web
  </div>
  <div class="nav-item" onclick="showSection('reservaciones', this)">
    <span class="nav-icon">📋</span> Reservaciones
  </div>
  <div class="nav-item" onclick="showSection('pagos', this)">
    <span class="nav-icon">💳</span> Pagos
  </div>
  <div class="sidebar-footer">
    <div class="api-badge">
      <div class="label">API Base URL</div>
      <div class="url">localhost:8001/api</div>
    </div>
  </div>
</aside>

<!-- MAIN -->
<main class="main">
  <div class="topbar">
    <div class="page-title" id="topbar-title">Dashboard</div>
    <div class="topbar-actions">
      <span style="font-size:12px;color:var(--text-muted);font-family:'DM Mono',monospace;">Http::facade</span>
      <div style="width:8px;height:8px;border-radius:50%;background:var(--mint);box-shadow:0 0 6px var(--mint);"></div>
    </div>
  </div>

  <div class="content">

    <!-- HOME -->
    <section id="sec-home" class="section active">
      <div class="welcome-hero">
        <div class="tag">✈ HttpClient_TravelNow</div>
        <h1>Panel de Administración</h1>
        <p>Proyecto que utiliza <strong>Http::facade</strong> de Laravel para comunicarse con la API de ADMIN_TRAVELNOW. Sin base de datos propia — todos los datos provienen de la API externa.</p>
      </div>
      <div class="stats-row" id="home-stats">
        <div class="stat-card">
          <div class="stat-icon" style="background:var(--sky-light)">🌍</div>
          <div class="stat-value" id="count-destinos">—</div>
          <div class="stat-label">Destinos</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:var(--gold-light)">🏨</div>
          <div class="stat-value" id="count-hoteles">—</div>
          <div class="stat-label">Hoteles</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:var(--purple-light)">🛏</div>
          <div class="stat-value" id="count-habitaciones">—</div>
          <div class="stat-label">Habitaciones</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:var(--coral-light)">✈</div>
          <div class="stat-value" id="count-vuelos">—</div>
          <div class="stat-label">Vuelos</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:var(--mint-light)">👤</div>
          <div class="stat-value" id="count-usuarios">—</div>
          <div class="stat-label">Usuarios</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:var(--sky-light)">👥</div>
          <div class="stat-value" id="count-clientes">—</div>
          <div class="stat-label">Clientes Web</div>
        </div>
      </div>
      <div class="grid-2">
        <div class="card">
          <div class="card-header"><span class="card-title">Cómo funciona HttpClient</span></div>
          <div class="card-body" style="padding:20px">
            <p style="font-size:13px;color:var(--text-muted);line-height:1.7;margin-bottom:16px">Este proyecto <strong>no tiene base de datos</strong>. Cada controlador usa <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-family:'DM Mono',monospace;font-size:11px">Http::get/post/put/delete()</code> para llamar a la API de ADMIN_TRAVELNOW.</p>
            <div class="code-block">
              <pre><span class="cm">// DestinoController.php</span>
<span class="kw">use</span> Illuminate\Support\Facades\<span class="fn">Http</span>;

<span class="kw">public function</span> <span class="fn">index</span>()
{
    <span class="var">$response</span> = <span class="fn">Http</span>::<span class="fn">get</span>(<span class="str">'http://localhost:8001/api/destinos'</span>);
    <span class="kw">return</span> response()-><span class="fn">json</span>(<span class="var">$response</span>-><span class="fn">json</span>(), <span class="var">$response</span>-><span class="fn">status</span>());
}</pre>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Rutas en web.php</span></div>
          <div class="card-body" style="padding:20px">
            <p style="font-size:13px;color:var(--text-muted);line-height:1.7;margin-bottom:16px">Todas las rutas están registradas en <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-family:'DM Mono',monospace;font-size:11px">routes/web.php</code> con <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-family:'DM Mono',monospace;font-size:11px">Route::resource</code>. Se movieron de api.php a web.php.</p>
            <div class="code-block">
              <pre><span class="cm">// routes/web.php</span>
<span class="fn">Route</span>::<span class="fn">resource</span>(<span class="str">'destinos'</span>, DestinoController::<span class="kw">class</span>);
<span class="fn">Route</span>::<span class="fn">resource</span>(<span class="str">'hoteles'</span>, HotelController::<span class="kw">class</span>);
<span class="fn">Route</span>::<span class="fn">resource</span>(<span class="str">'habitaciones'</span>, HabitacionController::<span class="kw">class</span>);
<span class="fn">Route</span>::<span class="fn">resource</span>(<span class="str">'vuelos'</span>, VueloController::<span class="kw">class</span>);
<span class="fn">Route</span>::<span class="fn">resource</span>(<span class="str">'usuarios'</span>, UsuarioController::<span class="kw">class</span>);
<span class="fn">Route</span>::<span class="fn">resource</span>(<span class="str">'reservaciones'</span>, ReservacionController::<span class="kw">class</span>);
<span class="fn">Route</span>::<span class="fn">resource</span>(<span class="str">'pagos'</span>, PagoController::<span class="kw">class</span>);</pre>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- DESTINOS -->
    <section id="sec-destinos" class="section">
      <div class="card-header" style="background:white;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:20px;padding:16px 24px;">
        <div>
          <div class="card-title">Controlador: DestinoController</div>
          <div style="font-family:'DM Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:3px">Http::get/post/put/delete → localhost:8001/api/destinos</div>
        </div>
        <button class="btn btn-primary" onclick="openModal('destino')">+ Nuevo Destino</button>
      </div>
      <div class="card">
        <div class="card-body">
          <div class="tbl-wrap">
            <table>
              <thead><tr><th>#</th><th>Nombre</th><th>País</th><th>Descripción</th><th>Acciones</th></tr></thead>
              <tbody id="tbody-destinos"><tr class="loading-row"><td colspan="5"><span class="spinner"></span> Cargando...</td></tr></tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- HOTELES -->
    <section id="sec-hoteles" class="section">
      <div class="card-header" style="background:white;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:20px;padding:16px 24px;">
        <div>
          <div class="card-title">Controlador: HotelController</div>
          <div style="font-family:'DM Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:3px">Http::get/post/put/delete → localhost:8001/api/hoteles</div>
        </div>
        <button class="btn btn-primary" onclick="openModal('hotel')">+ Nuevo Hotel</button>
      </div>
      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Nombre</th><th>Estrellas</th><th>Destino</th><th>Acciones</th></tr></thead>
            <tbody id="tbody-hoteles"><tr class="loading-row"><td colspan="5"><span class="spinner"></span></td></tr></tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- HABITACIONES -->
    <section id="sec-habitaciones" class="section">
      <div class="card-header" style="background:white;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:20px;padding:16px 24px;">
        <div>
          <div class="card-title">Controlador: HabitacionController</div>
          <div style="font-family:'DM Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:3px">Http::get/post/put/delete → localhost:8001/api/habitaciones</div>
        </div>
        <button class="btn btn-primary" onclick="openModal('habitacion')">+ Nueva Habitación</button>
      </div>
      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Tipo</th><th>Precio</th><th>Hotel ID</th><th>Acciones</th></tr></thead>
            <tbody id="tbody-habitaciones"><tr class="loading-row"><td colspan="5"><span class="spinner"></span></td></tr></tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- VUELOS -->
    <section id="sec-vuelos" class="section">
      <div class="card-header" style="background:white;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:20px;padding:16px 24px;">
        <div>
          <div class="card-title">Controlador: VueloController</div>
          <div style="font-family:'DM Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:3px">Http::get/post/put/delete → localhost:8001/api/vuelos</div>
        </div>
        <button class="btn btn-primary" onclick="openModal('vuelo')">+ Nuevo Vuelo</button>
      </div>
      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Aerolínea</th><th>Origen</th><th>Fecha Salida</th><th>Precio</th><th>Destino ID</th><th>Acciones</th></tr></thead>
            <tbody id="tbody-vuelos"><tr class="loading-row"><td colspan="7"><span class="spinner"></span></td></tr></tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- USUARIOS -->
    <section id="sec-usuarios" class="section">
      <div class="card-header" style="background:white;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:20px;padding:16px 24px;">
        <div>
          <div class="card-title">Controlador: UsuarioController</div>
          <div style="font-family:'DM Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:3px">Http::get/post/put/delete → localhost:8001/api/usuarios</div>
        </div>
        <button class="btn btn-primary" onclick="openModal('usuario')">+ Nuevo Usuario</button>
      </div>
      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Rol</th><th>Acciones</th></tr></thead>
            <tbody id="tbody-usuarios"><tr class="loading-row"><td colspan="6"><span class="spinner"></span></td></tr></tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- CLIENTES -->
    <section id="sec-clientes" class="section">
      <div class="card-header" style="background:white;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:20px;padding:16px 24px;">
        <div>
          <div class="card-title">Lista de Clientes (Web)</div>
          <div style="font-family:'DM Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:3px">Http::get → localhost:8001/api/clientes</div>
        </div>
      </div>
      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Registro</th></tr></thead>
            <tbody id="tbody-clientes"><tr class="loading-row"><td colspan="5"><span class="spinner"></span></td></tr></tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- RESERVACIONES -->
    <section id="sec-reservaciones" class="section">
      <div class="card-header" style="background:white;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:20px;padding:16px 24px;">
        <div>
          <div class="card-title">Controlador: ReservacionController</div>
          <div style="font-family:'DM Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:3px">Http::get/post/put/delete → localhost:8001/api/reservaciones</div>
        </div>
        <button class="btn btn-primary" onclick="openModal('reservacion')">+ Nueva Reservación</button>
      </div>
      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Usuario</th><th>Habitación</th><th>Vuelo</th><th>Acciones</th></tr></thead>
            <tbody id="tbody-reservaciones"><tr class="loading-row"><td colspan="7"><span class="spinner"></span></td></tr></tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- PAGOS -->
    <section id="sec-pagos" class="section">
      <div class="card-header" style="background:white;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:20px;padding:16px 24px;">
        <div>
          <div class="card-title">Controlador: PagoController</div>
          <div style="font-family:'DM Mono',monospace;font-size:11px;color:var(--text-muted);margin-top:3px">Http::get/post/put/delete → localhost:8001/api/pagos</div>
        </div>
        <button class="btn btn-primary" onclick="openModal('pago')">+ Nuevo Pago</button>
      </div>
      <div class="card">
        <div class="tbl-wrap">
          <table>
            <thead><tr><th>#</th><th>Monto</th><th>Método de Pago</th><th>Reservación ID</th><th>Acciones</th></tr></thead>
            <tbody id="tbody-pagos"><tr class="loading-row"><td colspan="5"><span class="spinner"></span></td></tr></tbody>
          </table>
        </div>
      </div>
    </section>

  </div>
</main>

<!-- TOASTER -->
<div class="toaster" id="toaster"></div>

<!-- MODAL DESTINO -->
<div class="modal-overlay" id="modal-destino">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title" id="modal-destino-title">Nuevo Destino</div>
      <button class="modal-close" onclick="closeModal('destino')">✕</button>
    </div>
    <div class="modal-body">
      <div class="code-block" style="margin-bottom:16px">
        <pre><span class="cm">// Http::post para crear | Http::put para editar</span>
<span class="var">$response</span> = <span class="fn">Http</span>::<span class="fn">post</span>(<span class="var">$this</span>-><span class="var">apiUrl</span>, [
    <span class="str">'nombre'</span>      => <span class="var">$request</span>-><span class="var">nombre</span>,
    <span class="str">'pais'</span>        => <span class="var">$request</span>-><span class="var">pais</span>,
    <span class="str">'descripcion'</span> => <span class="var">$request</span>-><span class="var">descripcion</span>,
]);</pre>
      </div>
      <input type="hidden" id="d-id"/>
      <div class="form-group">
        <label class="form-label">Nombre del destino</label>
        <input class="form-control" id="d-nombre" placeholder="ej. Cancún"/>
      </div>
      <div class="form-group">
        <label class="form-label">País</label>
        <input class="form-control" id="d-pais" placeholder="ej. México"/>
      </div>
      <div class="form-group">
        <label class="form-label">Descripción</label>
        <input class="form-control" id="d-desc" placeholder="Descripción del destino"/>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal('destino')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveDestino()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL HOTEL -->
<div class="modal-overlay" id="modal-hotel">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title" id="modal-hotel-title">Nuevo Hotel</div>
      <button class="modal-close" onclick="closeModal('hotel')">✕</button>
    </div>
    <div class="modal-body">
      <div class="code-block" style="margin-bottom:16px">
        <pre><span class="var">$response</span> = <span class="fn">Http</span>::<span class="fn">post</span>(<span class="var">$this</span>-><span class="var">apiUrl</span>, [
    <span class="str">'nombre'</span>     => <span class="var">$request</span>-><span class="var">nombre</span>,
    <span class="str">'estrellas'</span>  => <span class="var">$request</span>-><span class="var">estrellas</span>,
    <span class="str">'id_destino'</span> => <span class="var">$request</span>-><span class="var">id_destino</span>,
]);</pre>
      </div>
      <input type="hidden" id="h-id"/>
      <div class="form-group">
        <label class="form-label">Nombre del hotel</label>
        <input class="form-control" id="h-nombre" placeholder="ej. Grand Hyatt"/>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Estrellas</label>
          <select class="form-control" id="h-estrellas">
            <option>1</option><option>2</option><option>3</option><option selected>4</option><option>5</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">ID Destino</label>
          <select class="form-control" id="h-destino"></select>
        </div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal('hotel')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveHotel()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL HABITACION -->
<div class="modal-overlay" id="modal-habitacion">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title" id="modal-habitacion-title">Nueva Habitación</div>
      <button class="modal-close" onclick="closeModal('habitacion')">✕</button>
    </div>
    <div class="modal-body">
      <div class="code-block" style="margin-bottom:16px">
        <pre><span class="var">$response</span> = <span class="fn">Http</span>::<span class="fn">post</span>(<span class="var">$this</span>-><span class="var">apiUrl</span>, [
    <span class="str">'tipo'</span>     => <span class="var">$request</span>-><span class="var">tipo</span>,
    <span class="str">'precio'</span>   => <span class="var">$request</span>-><span class="var">precio</span>,
    <span class="str">'id_hotel'</span> => <span class="var">$request</span>-><span class="var">id_hotel</span>,
]);</pre>
      </div>
      <input type="hidden" id="hab-id"/>
      <div class="form-group">
        <label class="form-label">Tipo de habitación</label>
        <select class="form-control" id="hab-tipo">
          <option>Sencilla</option><option>Doble</option><option>Suite</option><option>Familiar</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Precio por noche ($)</label>
          <input class="form-control" id="hab-precio" type="number" placeholder="1500"/>
        </div>
        <div class="form-group">
          <label class="form-label">Hotel</label>
          <select class="form-control" id="hab-hotel"></select>
        </div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal('habitacion')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveHabitacion()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL VUELO -->
<div class="modal-overlay" id="modal-vuelo">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title" id="modal-vuelo-title">Nuevo Vuelo</div>
      <button class="modal-close" onclick="closeModal('vuelo')">✕</button>
    </div>
    <div class="modal-body">
      <div class="code-block" style="margin-bottom:16px">
        <pre><span class="var">$response</span> = <span class="fn">Http</span>::<span class="fn">post</span>(<span class="var">$this</span>-><span class="var">apiUrl</span>, [
    <span class="str">'aerolinea'</span>    => <span class="var">$request</span>-><span class="var">aerolinea</span>,
    <span class="str">'origen'</span>       => <span class="var">$request</span>-><span class="var">origen</span>,
    <span class="str">'fecha_salida'</span> => <span class="var">$request</span>-><span class="var">fecha_salida</span>,
    <span class="str">'precio'</span>       => <span class="var">$request</span>-><span class="var">precio</span>,
    <span class="str">'id_destino'</span>   => <span class="var">$request</span>-><span class="var">id_destino</span>,
]);</pre>
      </div>
      <input type="hidden" id="v-id"/>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Aerolínea</label>
          <input class="form-control" id="v-aerolinea" placeholder="Aeroméxico"/>
        </div>
        <div class="form-group">
          <label class="form-label">Origen</label>
          <input class="form-control" id="v-origen" placeholder="GDL"/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Fecha de salida</label>
          <input class="form-control" id="v-fecha" type="date"/>
        </div>
        <div class="form-group">
          <label class="form-label">Precio ($)</label>
          <input class="form-control" id="v-precio" type="number" placeholder="2500"/>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Destino</label>
        <select class="form-control" id="v-destino"></select>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal('vuelo')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveVuelo()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL USUARIO -->
<div class="modal-overlay" id="modal-usuario">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title" id="modal-usuario-title">Nuevo Usuario</div>
      <button class="modal-close" onclick="closeModal('usuario')">✕</button>
    </div>
    <div class="modal-body">
      <div class="code-block" style="margin-bottom:16px">
        <pre><span class="var">$response</span> = <span class="fn">Http</span>::<span class="fn">post</span>(<span class="var">$this</span>-><span class="var">apiUrl</span>, [
    <span class="str">'nombre'</span>   => <span class="var">$request</span>-><span class="var">nombre</span>,
    <span class="str">'correo'</span>   => <span class="var">$request</span>-><span class="var">correo</span>,
    <span class="str">'telefono'</span> => <span class="var">$request</span>-><span class="var">telefono</span>,
]);</pre>
      </div>
      <input type="hidden" id="u-id"/>
      <div class="form-group">
        <label class="form-label">Nombre completo</label>
        <input class="form-control" id="u-nombre" placeholder="Juan Pérez"/>
      </div>
      <div class="form-group">
        <label class="form-label">Correo electrónico</label>
        <input class="form-control" id="u-correo" type="email" placeholder="juan@email.com"/>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Contraseña</label>
          <input class="form-control" id="u-pass" type="password" placeholder="••••••••"/>
        </div>
        <div class="form-group">
          <label class="form-label">Teléfono</label>
          <input class="form-control" id="u-tel" placeholder="3311223344"/>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Rol</label>
        <select class="form-control" id="u-rol">
          <option value="recepcionista">Recepcionista</option>
          <option value="admin">Admin</option>
        </select>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal('usuario')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveUsuario()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL RESERVACION -->
<div class="modal-overlay" id="modal-reservacion">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title" id="modal-reservacion-title">Nueva Reservación</div>
      <button class="modal-close" onclick="closeModal('reservacion')">✕</button>
    </div>
    <div class="modal-body">
      <div class="code-block" style="margin-bottom:16px">
        <pre><span class="var">$response</span> = <span class="fn">Http</span>::<span class="fn">post</span>(<span class="var">$this</span>-><span class="var">apiUrl</span>, [
    <span class="str">'fecha_inicio'</span>  => <span class="var">$request</span>-><span class="var">fecha_inicio</span>,
    <span class="str">'fecha_fin'</span>     => <span class="var">$request</span>-><span class="var">fecha_fin</span>,
    <span class="str">'id_usuario'</span>    => <span class="var">$request</span>-><span class="var">id_usuario</span>,
    <span class="str">'id_habitacion'</span> => <span class="var">$request</span>-><span class="var">id_habitacion</span>,
    <span class="str">'id_vuelo'</span>      => <span class="var">$request</span>-><span class="var">id_vuelo</span>,
]);</pre>
      </div>
      <input type="hidden" id="r-id"/>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Fecha inicio</label>
          <input class="form-control" id="r-fi" type="date"/>
        </div>
        <div class="form-group">
          <label class="form-label">Fecha fin</label>
          <input class="form-control" id="r-ff" type="date"/>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Usuario</label>
          <select class="form-control" id="r-usuario"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Habitación</label>
          <select class="form-control" id="r-habitacion"></select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Vuelo (Opcional)</label>
        <select class="form-control" id="r-vuelo"></select>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal('reservacion')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveReservacion()">Guardar</button>
    </div>
  </div>
</div>

<!-- MODAL PAGO -->
<div class="modal-overlay" id="modal-pago">
  <div class="modal">
    <div class="modal-head">
      <div class="modal-title" id="modal-pago-title">Nuevo Pago</div>
      <button class="modal-close" onclick="closeModal('pago')">✕</button>
    </div>
    <div class="modal-body">
      <div class="code-block" style="margin-bottom:16px">
        <pre><span class="var">$response</span> = <span class="fn">Http</span>::<span class="fn">post</span>(<span class="var">$this</span>-><span class="var">apiUrl</span>, [
    <span class="str">'monto'</span>          => <span class="var">$request</span>-><span class="var">monto</span>,
    <span class="str">'metodo_pago'</span>    => <span class="var">$request</span>-><span class="var">metodo_pago</span>,
    <span class="str">'id_reservacion'</span> => <span class="var">$request</span>-><span class="var">id_reservacion</span>,
]);</pre>
      </div>
      <input type="hidden" id="p-id"/>
      <div class="form-group">
        <label class="form-label">Monto ($)</label>
        <input class="form-control" id="p-monto" type="number" placeholder="3500"/>
      </div>
      <div class="form-group">
        <label class="form-label">Método de pago</label>
        <select class="form-control" id="p-metodo">
          <option>Tarjeta de crédito</option><option>Tarjeta de débito</option>
          <option>Transferencia</option><option>Efectivo</option><option>PayPal</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Reservación</label>
        <select class="form-control" id="p-res"></select>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal('pago')">Cancelar</button>
      <button class="btn btn-primary" onclick="savePago()">Guardar</button>
    </div>
  </div>
</div>

<script>
// ============================================================
// CONFIG - Cambia esta URL por la de tu API de ADMIN_TRAVELNOW
// ============================================================
const API_BASE = "{{ rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/') }}";

// Estado local (simula respuesta de la API para demo sin conexión)
const localData = {
  destinos: [
    { id_destino: 1, nombre: 'Cancún', pais: 'México', descripcion: 'Paraíso caribeño con playas de arena blanca' },
    { id_destino: 2, nombre: 'Paris', pais: 'Francia', descripcion: 'La ciudad del amor y la moda' },
    { id_destino: 3, nombre: 'Tokyo', pais: 'Japón', descripcion: 'Metrópolis vibrante con cultura milenaria' },
  ],
  hoteles: [
    { id_hotel: 1, nombre: 'Grand Hyatt Cancún', estrellas: 5, id_destino: 1 },
    { id_hotel: 2, nombre: 'Hotel Ritz Paris', estrellas: 5, id_destino: 2 },
  ],
  habitaciones: [
    { id_habitacion: 1, tipo: 'Suite', precio: 4500, id_hotel: 1 },
    { id_habitacion: 2, tipo: 'Doble', precio: 2800, id_hotel: 2 },
  ],
  vuelos: [
    { id_vuelo: 1, aerolinea: 'Aeroméxico', origen: 'GDL', fecha_salida: '2026-05-15', precio: 3200, id_destino: 1 },
    { id_vuelo: 2, aerolinea: 'Air France', origen: 'MEX', fecha_salida: '2026-06-01', precio: 18500, id_destino: 2 },
  ],
  usuarios: [
    { id_usuario: 1, nombre: 'Ana García', correo: 'ana@email.com', telefono: '3312345678' },
    { id_usuario: 2, nombre: 'Luis Martínez', correo: 'luis@email.com', telefono: '3398765432' },
  ],
  reservaciones: [
    { id_reservacion: 1, fecha_inicio: '2026-05-15', fecha_fin: '2026-05-22', id_usuario: 1, id_habitacion: 1, id_vuelo: 1 },
  ],
  pagos: [
    { id_pago: 1, monto: 38000, metodo_pago: 'Tarjeta de crédito', id_reservacion: 1 },
  ],
  clientes: []
};
let nextId = { destinos: 4, hoteles: 3, habitaciones: 3, vuelos: 3, usuarios: 3, reservaciones: 2, pagos: 2, clientes: 1 };

// ============================================================
// NAVIGATION
// ============================================================
const titles = { home:'Dashboard', destinos:'Destinos', hoteles:'Hoteles', habitaciones:'Habitaciones', vuelos:'Vuelos', usuarios:'Usuarios', reservaciones:'Reservaciones', pagos:'Pagos' };

function showSection(name, el) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('sec-' + name).classList.add('active');
  el.classList.add('active');
  document.getElementById('topbar-title').textContent = titles[name];
  if (name !== 'home') loadTable(name);
  else loadStats();
}

// ============================================================
// API CALLS (con fallback a datos locales para demo)
// ============================================================
async function apiCall(method, endpoint, body = null) {
  try {
    const opts = { method, headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' } };
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch(API_BASE + endpoint, opts);
    if (!res.ok) throw new Error('API error');
    return await res.json();
  } catch (e) {
    // API no disponible → usar datos locales para demo
    return null;
  }
}

function getLocalKey(table) {
  const map = { destinos: 'destinos', hoteles: 'hoteles', habitaciones: 'habitaciones', vuelos: 'vuelos', usuarios: 'usuarios', reservaciones: 'reservaciones', pagos: 'pagos', clientes: 'clientes' };
  return map[table];
}

// ============================================================
// LOAD TABLE
// ============================================================
async function loadTable(table) {
  const tbody = document.getElementById('tbody-' + table);
  tbody.innerHTML = '<tr class="loading-row"><td colspan="10"><span class="spinner"></span> Cargando desde API...</td></tr>';
  let data = await apiCall('GET', '/' + table);
  
  // ✅ FIX: Si el API devuelve el formato envuelto {datos: [...]}, extraer los datos
  if (data && data.datos) {
    data = data.datos;
  }
  
  if (!data) data = localData[getLocalKey(table)];
  renderTable(table, data);
}

function renderTable(table, data) {
  const tbody = document.getElementById('tbody-' + table);
  if (!data || data.length === 0) { tbody.innerHTML = '<tr><td colspan="10"><div class="empty"><div class="empty-icon">○</div><p>Sin registros</p></div></td></tr>'; return; }
  tbody.innerHTML = data.map(row => buildRow(table, row)).join('');
}

function buildRow(table, row) {
  const pk = row.id || row.id_usuario || row.id_destino || row.id_hotel || row.id_habitacion || row.id_vuelo || row.id_reservacion || row.id_pago;
  const actions = `<td><div style="display:flex;gap:6px">
    <button class="btn btn-ghost btn-sm" onclick='editRow("${table}", ${JSON.stringify(row)})'>✏ Editar</button>
    <button class="btn btn-danger btn-sm" onclick='deleteRow("${table}", ${pk})'>✕ Borrar</button>
  </div></td>`;
  switch(table) {
    case 'destinos': return `<tr><td><span class="badge badge-sky">${pk}</span></td><td>${row.nombre}</td><td>${row.pais}</td><td style="color:var(--text-muted);font-size:12px">${row.descripcion||''}</td>${actions}</tr>`;
    case 'hoteles': return `<tr><td><span class="badge badge-gold">${pk}</span></td><td>${row.nombre}</td><td>${'★'.repeat(row.estrellas)}</td><td><span class="badge badge-sky">Destino #${row.id_destino}</span></td>${actions}</tr>`;
    case 'habitaciones': return `<tr><td><span class="badge badge-purple">${pk}</span></td><td>${row.tipo}</td><td>$${Number(row.precio).toLocaleString()}</td><td><span class="badge badge-gold">Hotel #${row.id_hotel}</span></td>${actions}</tr>`;
    case 'vuelos': return `<tr><td><span class="badge badge-coral">${pk}</span></td><td>${row.aerolinea}</td><td>${row.origen}</td><td>${row.fecha_salida}</td><td>$${Number(row.precio).toLocaleString()}</td><td><span class="badge badge-sky">#${row.id_destino}</span></td>${actions}</tr>`;
    case 'usuarios': return `<tr><td><span class="badge badge-mint">${pk}</span></td><td>${row.nombre}</td><td style="font-family:'DM Mono',monospace;font-size:12px">${row.correo}</td><td>${row.telefono||''}</td><td><span class="badge ${row.rol === 'admin' ? 'badge-coral' : 'badge-sky'}">${(row.rol||'recepcionista').toUpperCase()}</span></td>${actions}</tr>`;
    case 'clientes': return `<tr><td><span class="badge badge-sky">${row.id_cliente || row.id}</span></td><td>${row.nombre}</td><td style="font-family:'DM Mono',monospace;font-size:12px">${row.correo}</td><td>${row.telefono||'N/A'}</td><td>${row.created_at ? new Date(row.created_at).toLocaleDateString() : 'N/A'}</td></tr>`;
    case 'reservaciones': 
      const owner = row.id_cliente ? `<span class="badge badge-sky">Cliente #${row.id_cliente}</span>` : `<span class="badge badge-mint">Usuario #${row.id_usuario}</span>`;
      const hab = row.id_habitacion ? `<span class="badge badge-purple">#${row.id_habitacion}</span>` : '<span class="text-xs text-gray-400">N/A</span>';
      const vue = row.id_vuelo ? `<span class="badge badge-coral">#${row.id_vuelo}</span>` : '<span class="text-xs text-gray-400">N/A</span>';
      return `<tr><td><span class="badge badge-sky">${pk}</span></td><td>${row.fecha_inicio}</td><td>${row.fecha_fin}</td><td>${owner}</td><td>${hab}</td><td>${vue}</td>${actions}</tr>`;
    case 'pagos': return `<tr><td><span class="badge badge-gold">${pk}</span></td><td><strong>$${Number(row.monto).toLocaleString()}</strong></td><td>${row.metodo_pago}</td><td><span class="badge badge-sky">#${row.id_reservacion}</span></td>${actions}</tr>`;
  }
}

// ============================================================
// STATS
// ============================================================
async function loadStats() {
  const tables = ['destinos','hoteles','habitaciones','vuelos','usuarios','clientes'];
  for (const t of tables) {
    let data = await apiCall('GET', '/' + t);
    if (data && data.datos) data = data.datos; // ✅ FIX: Extraer datos
    if (!data) data = localData[t];
    
    const el = document.getElementById('count-' + t);
    if (el && data) el.textContent = data.length;
  }
}

// ============================================================
// MODALS
// ============================================================
function openModal(name) {
  document.getElementById('modal-' + name).classList.add('open');
  document.getElementById('modal-' + name + '-title').textContent = 'Nuevo ' + cap(name);
  clearModal(name);

  // Poblar Selects
  if(name === 'hotel') populateSelect('/destinos', 'h-destino', 'nombre', 'id', 'Destino');
  if(name === 'vuelo') populateSelect('/destinos', 'v-destino', 'nombre', 'id', 'Destino');
  if(name === 'habitacion') populateSelect('/hoteles', 'hab-hotel', 'nombre', 'id', 'Hotel');
  if(name === 'reservacion') {
      populateSelect('/usuarios', 'r-usuario', 'nombre', 'id_usuario', 'Usuario');
      populateSelect('/habitaciones', 'r-habitacion', 'tipo', 'id', 'Habitación');
      populateSelect('/vuelos', 'r-vuelo', 'aerolinea', 'id', 'Vuelo');
  }
  if(name === 'pago') populateSelect('/reservaciones', 'p-res', 'id', 'id', 'Reservación');
}

async function populateSelect(endpoint, selectId, textProp, valProp, label) {
    const select = document.getElementById(selectId);
    if(!select) return;
    select.innerHTML = '<option value="">Cargando...</option>';
    let data = await apiCall('GET', endpoint);
    if (data && data.datos) data = data.datos;
    if (!data || data.length === 0) {
        select.innerHTML = `<option value="">No hay ${label.toLowerCase()}s</option>`;
        return;
    }
    select.innerHTML = `<option value="">Seleccione un ${label.toLowerCase()}</option>` + 
        data.map(item => `<option value="${item[valProp]}">#${item[valProp]} - ${item[textProp]}</option>`).join('');
}
function closeModal(name) { document.getElementById('modal-' + name).classList.remove('open'); }
function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
function clearModal(n) {
  document.querySelectorAll('#modal-' + n + ' input, #modal-' + n + ' select').forEach(el => { if (el.type !== 'hidden') el.value = ''; else el.value = ''; });
}

function editRow(table, row) {
  const pk = row.id || row.id_usuario || row.id_destino || row.id_hotel || row.id_habitacion || row.id_vuelo || row.id_reservacion || row.id_pago;
  const modal = document.getElementById('modal-' + singMap(table));
  modal.classList.add('open');
  document.getElementById('modal-' + singMap(table) + '-title').textContent = 'Editar ' + cap(singMap(table));
  fillForm(table, row, pk);
}

function singMap(t) {
  return { destinos:'destino', hoteles:'hotel', habitaciones:'habitacion', vuelos:'vuelo', usuarios:'usuario', reservaciones:'reservacion', pagos:'pago', clientes:'cliente' }[t];
}

function fillForm(table, row, pk) {
  switch(table) {
    case 'destinos': document.getElementById('d-id').value=pk; document.getElementById('d-nombre').value=row.nombre; document.getElementById('d-pais').value=row.pais; document.getElementById('d-desc').value=row.descripcion||''; break;
    case 'hoteles': document.getElementById('h-id').value=pk; document.getElementById('h-nombre').value=row.nombre; document.getElementById('h-estrellas').value=row.estrellas; document.getElementById('h-destino').value=row.id_destino; break;
    case 'habitaciones': document.getElementById('hab-id').value=pk; document.getElementById('hab-tipo').value=row.tipo; document.getElementById('hab-precio').value=row.precio; document.getElementById('hab-hotel').value=row.id_hotel; break;
    case 'vuelos': document.getElementById('v-id').value=pk; document.getElementById('v-aerolinea').value=row.aerolinea; document.getElementById('v-origen').value=row.origen; document.getElementById('v-fecha').value=row.fecha_salida; document.getElementById('v-precio').value=row.precio; document.getElementById('v-destino').value=row.id_destino; break;
    case 'usuarios': document.getElementById('u-id').value=pk; document.getElementById('u-nombre').value=row.nombre; document.getElementById('u-correo').value=row.correo; document.getElementById('u-tel').value=row.telefono||''; document.getElementById('u-rol').value=row.rol||'recepcionista'; document.getElementById('u-pass').value=''; break;
    case 'reservaciones': document.getElementById('r-id').value=pk; document.getElementById('r-fi').value=row.fecha_inicio; document.getElementById('r-ff').value=row.fecha_fin; document.getElementById('r-usuario').value=row.id_usuario; document.getElementById('r-habitacion').value=row.id_habitacion; document.getElementById('r-vuelo').value=row.id_vuelo; break;
    case 'pagos': document.getElementById('p-id').value=pk; document.getElementById('p-monto').value=row.monto; document.getElementById('p-metodo').value=row.metodo_pago; document.getElementById('p-res').value=row.id_reservacion; break;
  }
}

// ============================================================
// SAVE HANDLERS
// ============================================================
async function saveDestino() {
  const id = document.getElementById('d-id').value;
  const body = { nombre: document.getElementById('d-nombre').value, pais: document.getElementById('d-pais').value, descripcion: document.getElementById('d-desc').value };
  let res = id ? await apiCall('PUT', '/destinos/' + id, body) : await apiCall('POST', '/destinos', body);
  if (!res) {
    if (id) { const i = localData.destinos.findIndex(x => x.id_destino == id); if(i>=0) localData.destinos[i] = {...localData.destinos[i], ...body}; }
    else { localData.destinos.push({ id_destino: nextId.destinos++, ...body }); }
  }
  closeModal('destino'); toast(id ? 'Destino actualizado' : 'Destino creado', 'success'); loadTable('destinos');
}

async function saveHotel() {
  const id = document.getElementById('h-id').value;
  const body = { nombre: document.getElementById('h-nombre').value, estrellas: +document.getElementById('h-estrellas').value, id_destino: +document.getElementById('h-destino').value };
  let res = id ? await apiCall('PUT', '/hoteles/' + id, body) : await apiCall('POST', '/hoteles', body);
  if (!res) {
    if (id) { const i = localData.hoteles.findIndex(x => x.id_hotel == id); if(i>=0) localData.hoteles[i] = {...localData.hoteles[i], ...body}; }
    else { localData.hoteles.push({ id_hotel: nextId.hoteles++, ...body }); }
  }
  closeModal('hotel'); toast(id ? 'Hotel actualizado' : 'Hotel creado', 'success'); loadTable('hoteles');
}

async function saveHabitacion() {
  const id = document.getElementById('hab-id').value;
  const body = { tipo: document.getElementById('hab-tipo').value, precio: +document.getElementById('hab-precio').value, id_hotel: +document.getElementById('hab-hotel').value };
  let res = id ? await apiCall('PUT', '/habitaciones/' + id, body) : await apiCall('POST', '/habitaciones', body);
  if (!res) {
    if (id) { const i = localData.habitaciones.findIndex(x => x.id_habitacion == id); if(i>=0) localData.habitaciones[i] = {...localData.habitaciones[i], ...body}; }
    else { localData.habitaciones.push({ id_habitacion: nextId.habitaciones++, ...body }); }
  }
  closeModal('habitacion'); toast(id ? 'Habitación actualizada' : 'Habitación creada', 'success'); loadTable('habitaciones');
}

async function saveVuelo() {
  const id = document.getElementById('v-id').value;
  const body = { aerolinea: document.getElementById('v-aerolinea').value, origen: document.getElementById('v-origen').value, fecha_salida: document.getElementById('v-fecha').value, precio: +document.getElementById('v-precio').value, id_destino: +document.getElementById('v-destino').value };
  let res = id ? await apiCall('PUT', '/vuelos/' + id, body) : await apiCall('POST', '/vuelos', body);
  if (!res) {
    if (id) { const i = localData.vuelos.findIndex(x => x.id_vuelo == id); if(i>=0) localData.vuelos[i] = {...localData.vuelos[i], ...body}; }
    else { localData.vuelos.push({ id_vuelo: nextId.vuelos++, ...body }); }
  }
  closeModal('vuelo'); toast(id ? 'Vuelo actualizado' : 'Vuelo creado', 'success'); loadTable('vuelos');
}

async function saveUsuario() {
  const id = document.getElementById('u-id').value;
  const body = { 
      nombre: document.getElementById('u-nombre').value, 
      correo: document.getElementById('u-correo').value, 
      telefono: document.getElementById('u-tel').value,
      rol: document.getElementById('u-rol').value
  };
  
  const pass = document.getElementById('u-pass').value;
  if(pass) body.password = pass; // Solo mandarla si la escribió (ej. al crear)

  let res = id ? await apiCall('PUT', '/usuarios/' + id, body) : await apiCall('POST', '/usuarios', body);
  if (!res) {
    if (id) { const i = localData.usuarios.findIndex(x => x.id_usuario == id); if(i>=0) localData.usuarios[i] = {...localData.usuarios[i], ...body}; }
    else { localData.usuarios.push({ id_usuario: nextId.usuarios++, ...body }); }
  }
  closeModal('usuario'); toast(id ? 'Usuario actualizado' : 'Usuario creado', 'success'); loadTable('usuarios');
}

async function saveReservacion() {
  const id = document.getElementById('r-id').value;
  const body = { fecha_inicio: document.getElementById('r-fi').value, fecha_fin: document.getElementById('r-ff').value, id_usuario: +document.getElementById('r-usuario').value, id_habitacion: +document.getElementById('r-habitacion').value, id_vuelo: +document.getElementById('r-vuelo').value };
  let res = id ? await apiCall('PUT', '/reservaciones/' + id, body) : await apiCall('POST', '/reservaciones', body);
  if (!res) {
    if (id) { const i = localData.reservaciones.findIndex(x => x.id_reservacion == id); if(i>=0) localData.reservaciones[i] = {...localData.reservaciones[i], ...body}; }
    else { localData.reservaciones.push({ id_reservacion: nextId.reservaciones++, ...body }); }
  }
  closeModal('reservacion'); toast(id ? 'Reservación actualizada' : 'Reservación creada', 'success'); loadTable('reservaciones');
}

async function savePago() {
  const id = document.getElementById('p-id').value;
  const body = { monto: +document.getElementById('p-monto').value, metodo_pago: document.getElementById('p-metodo').value, id_reservacion: +document.getElementById('p-res').value };
  let res = id ? await apiCall('PUT', '/pagos/' + id, body) : await apiCall('POST', '/pagos', body);
  if (!res) {
    if (id) { const i = localData.pagos.findIndex(x => x.id_pago == id); if(i>=0) localData.pagos[i] = {...localData.pagos[i], ...body}; }
    else { localData.pagos.push({ id_pago: nextId.pagos++, ...body }); }
  }
  closeModal('pago'); toast(id ? 'Pago actualizado' : 'Pago registrado', 'success'); loadTable('pagos');
}

// ============================================================
// DELETE
// ============================================================
async function deleteRow(table, id) {
  if (!confirm('¿Eliminar este registro?')) return;
  const endMap = { destinos:'/destinos/', hoteles:'/hoteles/', habitaciones:'/habitaciones/', vuelos:'/vuelos/', usuarios:'/usuarios/', reservaciones:'/reservaciones/', pagos:'/pagos/' };
  
  try {
    const opts = { method: 'DELETE', headers: { 'Accept': 'application/json' } };
    const res = await fetch(API_BASE + endMap[table] + id, opts);
    
    if (!res.ok) {
        throw new Error('No se puede eliminar. Es probable que este registro esté siendo usado por otros (ej. un destino con hoteles asignados).');
    }
    
    toast('Registro eliminado exitosamente', 'success'); 
    loadTable(table);
    loadStats();
  } catch(e) {
    toast(e.message, 'error');
  }
}

// ============================================================
// TOAST
// ============================================================
function toast(msg, type='success') {
  const t = document.createElement('div');
  t.className = `toast toast-${type}`;
  t.innerHTML = (type==='success' ? '✓' : '✗') + ' ' + msg;
  document.getElementById('toaster').appendChild(t);
  setTimeout(() => t.remove(), 3000);
}

// ============================================================
// INIT
// ============================================================
loadStats();
document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
});
</script>
</body>
</html>
<?php
// rutax-dashboard.php
// RutaX · Medellín Movilidata OS - Sistema de Gestión de Movilidad y Tránsito
<<<<<<< HEAD
// con integración de API del clima (Open-Meteo) y chatbot Botpress

$APP_NAME = 'RutaX · Medellín Movilidata OS';

// Configuración y carga de datos GeoJSON
=======

$APP_NAME = 'RutaX · Medellín Movilidata OS';

// Configuración y carga de datos GeoJSON reales
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
$geojsonFile = __DIR__ . '/data/total_incidentes_transito.geojson';
$geoJsonData = null;
$criticalCount = 0;
$totalIncidents = 0;

if (file_exists($geojsonFile)) {
    $geoJsonString = file_get_contents($geojsonFile);
    $geoJsonData = json_decode($geoJsonString, true);
    if ($geoJsonData && isset($geoJsonData['features'])) {
        $totalIncidents = count($geoJsonData['features']);
        foreach ($geoJsonData['features'] as $feature) {
            $props = $feature['properties'] ?? [];
            $gravedad = strtoupper($props['gravedad'] ?? $props['tipo'] ?? '');
            if (strpos($gravedad, 'MUERTO') !== false || strpos($gravedad, 'FATAL') !== false || strpos($gravedad, 'DECESO') !== false || $gravedad === 'MORTAL') {
                $criticalCount++;
            }
        }
    } else {
        $geoJsonData = null;
    }
}

<<<<<<< HEAD
=======
// Respaldo de simulación si el archivo GeoJSON no existe en el servidor local
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
if (!$geoJsonData) {
    $features = [];
    $comunas = ['Popular', 'Santa Cruz', 'Manrique', 'Aranjuez', 'Castilla', 'Doce de Octubre', 'Robledo', 'Villa Hermosa', 'Buenos Aires', 'La Candelaria', 'Laureles', 'El Poblado'];
    $barrios = ['Belén', 'La América', 'San Javier', 'El Poblado', 'Envigado', 'Itagüí', 'Sabaneta', 'La Floresta', 'Estadio', 'Carlos E. Restrepo'];
    $tipos = ['Choque', 'Atropello', 'Caída de moto', 'Daños materiales', 'Colisión múltiple', 'Obstrucción'];
    $gravedades = ['Leve', 'Grave', 'Mortal', 'Sin lesionados', 'Hospitalización'];
    
    for ($i = 0; $i < 45; $i++) {
        $lng = -75.59 + (mt_rand(-80, 80) / 1000);
        $lat = 6.24 + (mt_rand(-60, 60) / 1000);
        $tipo = $tipos[array_rand($tipos)];
        $gravedad = $gravedades[array_rand($gravedades)];
        if ($gravedad === 'Mortal') $criticalCount++;
        
        $features[] = [
            'type' => 'Feature',
            'geometry' => ['type' => 'Point', 'coordinates' => [$lng, $lat]],
            'properties' => [
                'id' => $i, 'clase' => $tipo, 'tipo' => $tipo, 'gravedad' => $gravedad,
                'direccion' => 'Cra ' . rand(1,100) . ' #' . rand(1,50) . '-' . rand(1,99),
                'barrio' => $barrios[array_rand($barrios)], 'comuna' => $comunas[array_rand($comunas)],
                'fecha' => date('Y-m-d', strtotime('-' . rand(0,5) . ' days')),
                'hora' => sprintf('%02d:%02d', rand(6,22), rand(0,59))
            ]
        ];
    }
    $totalIncidents = count($features);
    $geoJsonData = ['type' => 'FeatureCollection', 'features' => $features];
}

$baseCongestion = min(85, 20 + floor($totalIncidents / 2.5));
$avgSpeed = max(12, 45 - floor($baseCongestion / 2.2));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title><?= htmlspecialchars($APP_NAME) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<<<<<<< HEAD
  
  <!-- Scripts de Botpress para el chatbot -->
  <script src="https://cdn.botpress.cloud/webchat/v3.6/inject.js"></script>
  <script src="https://files.bpcontent.cloud/2026/05/27/17/20260527172542-3Z3UA6G3.js" defer></script>
  
=======
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
  <style>
    :root{
      --bg:#0b1220; --card:#111a2e; --border:#1f2a44;
      --fg:#e6edf7; --muted:#8a97b2;
      --primary:#0F4C81; --accent:#F9A825;
      --ok:#10b981; --warn:#f59e0b; --danger:#ef4444;
    }
    *{box-sizing:border-box}
    html,body{margin:0;height:100%;background:var(--bg);color:var(--fg);font-family:'Inter',system-ui,sans-serif;overflow:hidden}
    #map{position:absolute;inset:0;z-index:0;background:#0b1220}
    
    .topbar{position:absolute;inset:1rem 1rem auto 1rem;z-index:20;display:flex;justify-content:space-between;align-items:flex-start;pointer-events:none}
    .pill{pointer-events:auto;display:inline-flex;align-items:center;gap:.5rem;padding:.65rem 1rem;border-radius:.85rem;
      background:rgba(17,26,46,.88);border:1px solid var(--border);backdrop-filter:blur(10px);
      color:var(--fg);font-weight:600;font-size:.9rem;cursor:pointer;box-shadow:0 8px 24px rgba(0,0,0,.35);transition:.2s}
    .pill:hover{background:rgba(17,26,46,1)}
    .pill.primary{background:var(--primary);border-color:var(--primary)}
    .pill.primary:hover{filter:brightness(1.1)}
    
    @media (max-width: 640px) {
      .pill span { display: none; }
      .app-title-text { display: none; }
    }

    .live{display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--ok);box-shadow:0 0 0 0 rgba(16,185,129,.6);animation:pulse 1.8s infinite}
    @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(16,185,129,.6)}70%{box-shadow:0 0 0 10px rgba(16,185,129,0)}100%{box-shadow:0 0 0 0 rgba(16,185,129,0)}}
    
    .panel{position:absolute;top:0;height:100%;background:rgba(17,26,46,.96);backdrop-filter:blur(14px);
      border-color:var(--border);box-shadow:0 20px 60px rgba(0,0,0,.5);z-index:30;
      display:flex;flex-direction:column;transition:transform .45s cubic-bezier(.22,.61,.36,1);width:100%;}
    
    @media (min-width: 640px) {
      .sidebar { width: 340px; }
<<<<<<< HEAD
=======
      .chat { width: 380px; }
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
    }
    
    .sidebar{left:0;border-right:1px solid var(--border);transform:translateX(0)}
    .sidebar.hidden{transform:translateX(-100%)}
<<<<<<< HEAD
=======
    .chat{right:0;border-left:1px solid var(--border);transform:translateX(100%)}
    .chat.open{transform:translateX(0)}
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
    
    .panel header{display:flex;justify-content:space-between;align-items:center;padding:1.1rem 1.2rem;border-bottom:1px solid var(--border)}
    .panel header h3{margin:0;font-size:1.05rem;font-weight:700}
    .panel header small{color:var(--muted);font-size:.72rem}
    .icon-btn{background:transparent;border:none;color:var(--fg);cursor:pointer;padding:.4rem;border-radius:.5rem}
    .icon-btn:hover{background:rgba(255,255,255,.08)}
    
    .scroll{overflow-y:auto;flex:1}
    .scroll::-webkit-scrollbar{width:6px} .scroll::-webkit-scrollbar-thumb{background:var(--border);border-radius:3px}
    .stats{display:grid;grid-template-columns:1fr 1fr;gap:.65rem;padding:0 1rem}
    .stat{background:rgba(11,18,32,.65);border:1px solid var(--border);border-radius:.85rem;padding:.75rem}
    .stat .label{display:flex;align-items:center;gap:.4rem;font-size:.72rem;color:var(--muted);font-weight:500}
    .stat .value{font-size:1.35rem;font-weight:800;margin-top:.2rem;letter-spacing:-.02em}
    .stat .hint{font-size:.68rem;color:var(--muted);margin-top:.1rem}
    .ok{color:var(--ok)} .warn{color:var(--warn)} .danger{color:var(--danger)}
    
<<<<<<< HEAD
=======
    .rain-btn{display:flex;align-items:center;justify-content:center;gap:.5rem;width:calc(100% - 2rem);
      margin:1rem;padding:.7rem 1rem;border-radius:.65rem;border:none;cursor:pointer;
      font-weight:600;font-size:.88rem;background:var(--primary);color:#fff;transition:.2s}
    .rain-btn:hover{filter:brightness(1.1)} .rain-btn.active{background:var(--danger)}
    
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
    nav.menu{padding:.5rem .75rem 1.25rem}
    nav.menu .title{padding:.5rem .5rem;font-size:.68rem;font-weight:600;color:var(--muted);letter-spacing:.08em;text-transform:uppercase}
    nav.menu a{display:flex;align-items:center;gap:.7rem;padding:.65rem .75rem;border-radius:.55rem;color:#cbd5e1;text-decoration:none;font-size:.88rem;font-weight:500;transition:.15s}
    nav.menu a i{color:var(--accent);width:18px;text-align:center}
    nav.menu a:hover{background:rgba(255,255,255,.06);color:#fff}
    
    .panel footer{padding:.85rem 1rem;border-top:1px solid var(--border);font-size:.7rem;color:var(--muted);line-height:1.45}
<<<<<<< HEAD
    
    /* Estilos para la tarjeta del clima */
    .weather-card {
      background: linear-gradient(135deg, rgba(15,76,129,0.2), rgba(0,0,0,0.2));
      border: 1px solid var(--border);
      border-radius: 1rem;
      margin: 1rem 1rem 0 1rem;
      padding: 0.75rem;
      backdrop-filter: blur(4px);
    }
    .weather-temp {
      font-size: 2rem;
      font-weight: 800;
      line-height: 1;
    }
    .weather-desc {
      font-size: 0.75rem;
      text-transform: capitalize;
    }
    .weather-update {
      font-size: 0.6rem;
      color: var(--muted);
      text-align: right;
      margin-top: 0.5rem;
    }
=======
    .msgs{flex:1;overflow-y:auto;padding:1rem;display:flex;flex-direction:column;gap:.6rem}
    .msg{max-width:85%;padding:.55rem .8rem;border-radius:1rem;font-size:.88rem;line-height:1.35}
    .msg.bot{align-self:flex-start;background:rgba(255,255,255,.07)}
    .msg.user{align-self:flex-end;background:var(--primary);color:#fff}
    
    .chat-input{display:flex;gap:.5rem;padding:.75rem;border-top:1px solid var(--border)}
    .chat-input input{flex:1;background:var(--bg);border:1px solid var(--border);color:var(--fg);border-radius:.55rem;padding:.55rem .75rem;outline:none;font-size:.88rem}
    .chat-input input:focus{border-color:var(--primary)}
    .chat-input button{background:var(--primary);color:#fff;border:none;padding:.55rem .85rem;border-radius:.55rem;cursor:pointer}
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
    
    .alert-btn{position:relative;overflow:hidden;transition:all 0.3s ease}
    .alert-btn:hover{transform:translateY(-4px);box-shadow:0 0 20px rgba(239,68,68,0.4)}
    .death-alert{animation:pulse-red 2s infinite}
    @keyframes pulse-red{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,0.6)}70%{box-shadow:0 0 0 15px rgba(239,68,68,0)}}
    
    /* Popups Estilizados */
    .enhanced-popup .leaflet-popup-content-wrapper{background:rgba(11,18,32,0.95);backdrop-filter:blur(8px);border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.5);padding:0;overflow:hidden}
    .enhanced-popup .leaflet-popup-content {margin:0;width:280px !important}
    .enhanced-popup .leaflet-popup-tip{background:rgba(11,18,32,0.95)}
    .custom-popup{color:#fff;font-family:'Inter',sans-serif}
    .popup-header{display:flex;align-items:center;gap:8px;padding:12px 16px;font-weight:800;font-size:0.85rem;border-bottom:1px solid rgba(255,255,255,0.1)}
    .popup-content{padding:12px 16px;display:flex;flex-direction:column;gap:6px}
    .popup-row{display:flex;justify-content:space-between;align-items:baseline;font-size:0.78rem;border-bottom:1px dashed rgba(255,255,255,0.08);padding-bottom:4px}
    .popup-label{font-weight:600;color:var(--muted)}
    .popup-value{text-align:right;font-weight:500;color:#f1f5f9}
    .gravedad-destacada{font-weight:800;text-transform:uppercase;padding:2px 6px;border-radius:10px;font-size:0.7rem}
    
    .popup-mortal .popup-header{background:#b91c1c} .popup-mortal .gravedad-destacada{background:rgba(239,68,68,0.2);color:#ff6b6b}
    .popup-grave .popup-header{background:#ea580c} .popup-grave .gravedad-destacada{background:rgba(249,115,22,0.2);color:#ffb347}
    .popup-leve .popup-header{background:#1e3a8a} .popup-leve .gravedad-destacada{background:rgba(59,130,246,0.2);color:#93c5fd}
    
    /* Modales */
    .modal-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;visibility:hidden;opacity:0;transition:all 0.2s ease}
    .modal-overlay.active{visibility:visible;opacity:1}
    .modal-container{background:rgba(17,26,46,0.98);backdrop-filter:blur(12px);border-radius:1.5rem;border:1px solid var(--border);width:90%;max-width:650px;max-height:85vh;display:flex;flex-direction:column;box-shadow:0 20px 40px rgba(0,0,0,0.6)}
    .modal-header{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.5rem;border-bottom:1px solid var(--border)}
    .incident-list{flex:1;overflow-y:auto;padding:1rem}
    
    .incident-card{background:rgba(11,18,32,0.5);border:1px solid var(--border);border-radius:1rem;padding:0.8rem 1rem;margin-bottom:0.6rem}
    .gravedad-badge{font-size:0.7rem;padding:0.2rem 0.6rem;border-radius:20px;font-weight:700}
    .gravedad-mortal{background:rgba(239,68,68,0.15);color:#ff6b6b}
    .gravedad-grave{background:rgba(249,115,22,0.15);color:#ffb347}
    .gravedad-leve{background:rgba(234,179,8,0.15);color:#fde047}
    .btn-view-map{background:var(--primary);color:white;padding:0.35rem 0.85rem;border-radius:0.5rem;font-size:0.75rem;cursor:pointer;margin-top:0.5rem;font-weight:500;transition:0.15s}
    .btn-view-map:hover{filter:brightness(1.2)}
  </style>
</head>
<body>

  <div id="map"></div>

  <div class="topbar">
    <button class="pill" id="btnMenu"><i class="fa-solid fa-bars"></i><span>Menú</span></button>
    <div class="pill" style="cursor:default"><span class="live"></span><span class="app-title-text"><?= htmlspecialchars($APP_NAME) ?></span><span class="sm:hidden">RutaX</span></div>
<<<<<<< HEAD
    <!-- Botón "Agente IA" abre el chat de Botpress (el script ya inyecta su propio botón flotante, pero lo dejamos por si acaso) -->
    <button class="pill primary" id="btnChat"><i class="fa-solid fa-robot"></i><span>Agente IA</span></button>
=======
    <div style="display: flex; gap: 0.5rem;">
      <button class="pill" id="btnListIncidents"><i class="fa-solid fa-list"></i><span>Incidentes</span></button>
      <button class="pill primary" id="btnChat"><i class="fa-solid fa-robot"></i><span>Agente IA</span></button>
    </div>
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
  </div>

  <div id="incidentModal" class="modal-overlay">
    <div class="modal-container">
      <div class="modal-header">
        <h3 class="text-lg font-bold flex items-center gap-2"><i class="fa-solid fa-car-crash text-amber-500"></i> Lista de Incidentes Activos</h3>
        <button id="closeModalBtn" class="text-xl text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div id="incidentListContainer" class="incident-list"></div>
    </div>
  </div>

  <aside class="panel sidebar hidden" id="sidebar">
    <header>
      <div><h3>MIMS</h3><small>Medellín Movilidata OS</small></div>
      <button class="icon-btn" id="closeSidebar"><i class="fa-solid fa-xmark"></i></button>
    </header>
    <div class="scroll">
<<<<<<< HEAD
      <!-- Tarjeta del Clima Integrada -->
      <div class="weather-card" id="weatherCard">
        <div class="flex justify-between items-center">
          <div>
            <div class="weather-temp" id="weatherTemp">--°C</div>
            <div class="weather-desc" id="weatherDesc">Cargando clima...</div>
          </div>
          <div class="text-4xl" id="weatherIcon">
            <i class="fa-solid fa-cloud-sun"></i>
          </div>
        </div>
        <div class="flex justify-between mt-2 text-xs text-slate-400">
          <span><i class="fa-solid fa-droplet"></i> <span id="weatherHumidity">--</span>%</span>
          <span><i class="fa-solid fa-wind"></i> <span id="weatherWind">--</span> km/h</span>
          <span><i class="fa-solid fa-temperature-low"></i> <span id="weatherFeels">--</span>°C</span>
        </div>
        <div class="weather-update" id="weatherUpdate"></div>
      </div>
=======
      <button class="rain-btn" id="rainBtn"><i class="fa-solid fa-cloud-showers-heavy"></i><span id="rainLabel">Simular lluvia intensa</span></button>
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
      
      <div class="p-4 space-y-3">
        <div class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1 flex items-center gap-2"><i class="fa-solid fa-bell"></i> Filtros de Consola</div>
        <button id="alertAllBtn" class="alert-btn w-full bg-slate-800 border border-slate-700 text-white p-3 rounded-xl font-semibold flex items-center gap-3 text-left">
          <i class="fa-solid fa-car-crash text-amber-500 text-xl"></i><div><div class="text-sm">Todos los Incidentes</div><div class="text-xs opacity-60" id="lblTotalRecs"><?= $totalIncidents ?> registros</div></div>
        </button>
        <button id="alertDeathsBtn" class="alert-btn death-alert w-full bg-gradient-to-r from-red-950 to-red-900 border border-red-700 text-white p-3 rounded-xl font-semibold flex items-center gap-3 text-left">
          <i class="fa-solid fa-skull-crossbones text-red-500 text-xl"></i><div><div class="text-sm flex items-center gap-2">Casos Críticos / Mortales</div><div class="text-xs text-red-300 opacity-80" id="lblCritRecs"><?= $criticalCount ?> alertas activas</div></div>
        </button>
      </div>

      <div class="stats">
        <div class="stat"><div class="label warn"><i class="fa-solid fa-chart-line"></i>Congestión</div><div class="value" id="sCong"><?= $baseCongestion ?>%</div><div class="hint" id="sCongHint">Tiempo real</div></div>
        <div class="stat"><div class="label danger"><i class="fa-solid fa-triangle-exclamation"></i>Puntos críticos</div><div class="value" id="sCrit"><?= $criticalCount ?></div><div class="hint">Fatalidades/Graves</div></div>
        <div class="stat"><div class="label ok" id="sFloodLabel"><i class="fa-solid fa-droplet"></i>Inundación</div><div class="value" id="sFlood">Bajo</div><div class="hint">Quebradas</div></div>
        <div class="stat"><div class="label ok"><i class="fa-solid fa-gauge-high"></i>Velocidad</div><div class="value" id="sSpeed"><?= $avgSpeed ?> km/h</div><div class="hint">Vías principales</div></div>
      </div>

      <nav class="menu">
        <div class="title">Navegación</div>
<<<<<<< HEAD
        <a href="<?= APP_URL ?>/app/views/home/Bienvenida.php"><i class="fa-solid fa-map-location-dot"></i> Bienvenida</a>
        <a href="<?= APP_URL ?>/app/views/home/Infracciones.php"><i class="fa-solid fa-map-location-dot"></i> Infracciones</a>
        <a href="<?= APP_URL ?>/app/views/home/Reglamento.php"><i class="fa-solid fa-file-lines"></i> Reglamento</a>
        <a href="<?= APP_URL ?>/app/views/home/Agentes.php"><i class="fa-solid fa-scale-balanced"></i> Agentes</a>
        <a href="<?= APP_URL ?>/app/views/home/Veedores.php"><i class="fa-solid fa-shield-halved"></i> Veedores</a>
        <a href="<?= APP_URL ?>/app/views/home/Abogados.php"><i class="fa-solid fa-users"></i> Abogados</a>
        <a href="<?= APP_URL ?>/app/views/home/Audiencias.php"><i class="fa-solid fa-chart-pie"></i> Audiencias</a>
        <a href="<?= APP_URL ?>/app/views/home/Reportes.php"><i class="fa-solid fa-chart-pie"></i> Reportes</a>
=======
        <a href="app/views/home/Bienvenida.php"><i class="fa-solid fa-map-location-dot"></i> Bienvenida</a>
        <a href="app/views/home/Infracciones.php"><i class="fa-solid fa-map-location-dot"></i> Infracciones</a>
        <a href="app/views/home/Reglamento.php"><i class="fa-solid fa-file-lines"></i> Reglamento</a>
        <a href="app/views/home/Agentes.php"><i class="fa-solid fa-scale-balanced"></i> Agentes</a>
        <a href="app/views/home/Veedores.php"><i class="fa-solid fa-shield-halved"></i> Veedores</a>
        <a href="app/views/home/Abogados.php"><i class="fa-solid fa-users"></i> Abogados</a>
        <a href="app/views/home/Audiencias.php"><i class="fa-solid fa-chart-pie"></i> Audiencias</a>
        <a href="app/views/home/Reportes.php"><i class="fa-solid fa-chart-pie"></i> Reportes</a>
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
      </nav>
    </div>
    <footer>RutaX · TransiControl · SIMIT · <?= $totalIncidents ?> incidentes en malla</footer>
  </aside>

<<<<<<< HEAD
  <script>
    const incidentsGeoJSON = <?= json_encode($geoJsonData) ?>;
    let currentIncidents = JSON.parse(JSON.stringify(incidentsGeoJSON));
    let map = null;
    let mainClusterGroup = null;
=======
  <aside class="panel chat" id="chat">
    <header>
      <div class="flex gap-2 items-center"><i class="fa-solid fa-robot text-amber-500"></i><div><h3>Agente Predictivo OS</h3><small>Movilidata-LLM-v1</small></div></div>
      <button class="icon-btn" id="closeChat"><i class="fa-solid fa-xmark"></i></button>
    </header>
    <div class="msgs" id="msgs">
      <div class="msg bot">🚦 Hola, soy tu asistente táctico de movilidad. Detecto <strong><?= $totalIncidents ?></strong> incidentes en la base de datos vial, de los cuales <strong><?= $criticalCount ?></strong> requieren despliegue prioritario de criminalística o grúa.</div>
    </div>
    <div class="chat-input">
      <input id="chatInput" type="text" placeholder="Ej: ¿Cuál es el nivel de congestión?">
      <button id="chatSend"><i class="fa-solid fa-paper-plane"></i></button>
    </div>
  </aside>

  <script>
    // Inyección segura de datos GeoJSON desde PHP
    const incidentsGeoJSON = <?= json_encode($geoJsonData) ?>;
    let currentIncidents = JSON.parse(JSON.stringify(incidentsGeoJSON));
    
    let rainActive = false;
    let map = null;
    let mainClusterGroup = null;
    let rainLayerGroup = null;
    let currentRainIncidents = [];
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
    
    let totalIncidentsCount = <?= $totalIncidents ?>;
    let criticalCount = <?= $criticalCount ?>;
    let congestionPercent = <?= $baseCongestion ?>;
    let avgSpeedKmh = <?= $avgSpeed ?>;
    let isModalOpen = false;
<<<<<<< HEAD
    
    const MEDELLIN_LAT = 6.2476;
    const MEDELLIN_LON = -75.5658;
    
    // Clima real (Open-Meteo)
    async function fetchWeather() {
      try {
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${MEDELLIN_LAT}&longitude=${MEDELLIN_LON}&current=temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m&timezone=auto`;
        const response = await fetch(url);
        const data = await response.json();
        
        if (data && data.current) {
          const weatherCode = data.current.weather_code;
          let iconClass = 'fa-cloud-sun';
          let weatherDesc = '';
          
          if (weatherCode === 0) { iconClass = 'fa-sun'; weatherDesc = 'Despejado'; }
          else if (weatherCode === 1 || weatherCode === 2) { iconClass = 'fa-cloud-sun'; weatherDesc = 'Parcialmente nublado'; }
          else if (weatherCode === 3) { iconClass = 'fa-cloud'; weatherDesc = 'Nublado'; }
          else if (weatherCode >= 45 && weatherCode <= 48) { iconClass = 'fa-smog'; weatherDesc = 'Niebla'; }
          else if (weatherCode >= 51 && weatherCode <= 55) { iconClass = 'fa-cloud-rain'; weatherDesc = 'Llovizna'; }
          else if (weatherCode >= 61 && weatherCode <= 65) { iconClass = 'fa-cloud-showers-heavy'; weatherDesc = 'Lluvia'; }
          else if (weatherCode >= 71 && weatherCode <= 77) { iconClass = 'fa-snowflake'; weatherDesc = 'Nieve'; }
          else if (weatherCode >= 80 && weatherCode <= 82) { iconClass = 'fa-cloud-rain'; weatherDesc = 'Chubascos'; }
          else if (weatherCode >= 95 && weatherCode <= 99) { iconClass = 'fa-cloud-bolt'; weatherDesc = 'Tormenta'; }
          else { iconClass = 'fa-cloud'; weatherDesc = 'Variable'; }
          
          document.getElementById('weatherTemp').innerHTML = `${Math.round(data.current.temperature_2m)}°C`;
          document.getElementById('weatherDesc').innerHTML = weatherDesc;
          document.getElementById('weatherHumidity').innerHTML = data.current.relative_humidity_2m || '--';
          document.getElementById('weatherWind').innerHTML = Math.round(data.current.wind_speed_10m) || '--';
          document.getElementById('weatherFeels').innerHTML = Math.round(data.current.apparent_temperature) || '--';
          document.getElementById('weatherIcon').innerHTML = `<i class="fa-solid ${iconClass}"></i>`;
          document.getElementById('weatherUpdate').innerHTML = `Actualizado: ${new Date().toLocaleTimeString()}`;
        } else {
          throw new Error('Datos no disponibles');
        }
      } catch (error) {
        console.error('Error al obtener el clima:', error);
        document.getElementById('weatherDesc').innerHTML = 'Error al cargar clima';
        document.getElementById('weatherIcon').innerHTML = '<i class="fa-solid fa-exclamation-triangle"></i>';
      }
    }
    
    fetchWeather();
    setInterval(fetchWeather, 900000);

    // Inicializar mapa
    function initMap() {
      map = L.map('map', { zoomControl: false }).setView([MEDELLIN_LAT, MEDELLIN_LON], 13);
=======

    // Inicialización del Entorno de Mapas
    function initMap() {
      map = L.map('map', { zoomControl: false }).setView([6.2476, -75.5658], 13);
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
      L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap · CARTO', maxZoom: 19
      }).addTo(map);
      
      L.control.zoom({ position: window.innerWidth < 640 ? 'topright' : 'bottomright' }).addTo(map);
      
      mainClusterGroup = L.markerClusterGroup({ maxClusterRadius: 50, spiderfyOnMaxZoom: true, showCoverageOnHover: false });
      map.addLayer(mainClusterGroup);
<<<<<<< HEAD
=======
      
      rainLayerGroup = L.layerGroup().addTo(map);
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
      loadIncidentsToMap(currentIncidents);
    }

    function loadIncidentsToMap(geojsonData, filterMortalOnly = false) {
      mainClusterGroup.clearLayers();
      
      L.geoJSON(geojsonData, {
        filter: (feature) => {
          if (!filterMortalOnly) return true;
          const g = (feature.properties.gravedad || feature.properties.tipo || '').toString().toUpperCase();
          return g.includes('MUERTO') || g.includes('FATAL') || g.includes('DECESO') || g === 'MORTAL';
        },
        pointToLayer: (feature, latlng) => {
          const props = feature.properties;
<<<<<<< HEAD
          let color = '#3b82f6';
          const gravedad = (props.gravedad || props.tipo || '').toString().toUpperCase();
          if (gravedad.includes('MUERTO') || gravedad.includes('FATAL') || gravedad.includes('DECESO') || gravedad === 'MORTAL') color = '#ef4444';
          else if (gravedad.includes('GRAVE') || gravedad.includes('HOSPITAL')) color = '#f97316';
          else if (gravedad.includes('LEVE')) color = '#eab308';
=======
          let color = '#3b82f6'; // Leve/Por defecto
          const gravedad = (props.gravedad || props.tipo || '').toString().toUpperCase();
          
          if (gravedad.includes('MUERTO') || gravedad.includes('FATAL') || gravedad.includes('DECESO') || gravedad === 'MORTAL') color = '#ef4444';
          else if (gravedad.includes('GRAVE') || gravedad.includes('HOSPITAL')) color = '#f97316';
          else if (gravedad.includes('LEVE')) color = '#eab308';
          
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
          return L.circleMarker(latlng, { radius: 7, fillColor: color, color: '#0b1220', weight: 1.5, opacity: 1, fillOpacity: 0.9 });
        },
        onEachFeature: (feature, layer) => {
          const p = feature.properties;
          const gravedad = (p.gravedad || '').toString().toUpperCase();
          const tipoIncidente = p.clase || p.tipo || 'Incidente';
          
          let nivelClase = 'popup-leve', titulo = '📌 INCIDENTE REGULAR', icono = '🚗';
          if (gravedad.includes('MUERTO') || gravedad.includes('FATAL') || gravedad.includes('DECESO') || gravedad === 'MORTAL') { 
            nivelClase = 'popup-mortal'; titulo = '⚠️ CASO FATAL'; icono = '💀'; 
          } else if (gravedad.includes('GRAVE') || gravedad.includes('HOSPITAL')) { 
            nivelClase = 'popup-grave'; titulo = '🚨 ALERTA CRÍTICA'; icono = '⚠️'; 
          }
          
          let emoji = '🚨';
          if(tipoIncidente.toLowerCase().includes('choque')) emoji = '💥';
          else if(tipoIncidente.toLowerCase().includes('moto')) emoji = '🏍️';
          else if(tipoIncidente.toLowerCase().includes('atropello')) emoji = '🚶‍♂️';

          const popupHtml = `
            <div class="custom-popup ${nivelClase}">
              <div class="popup-header"><span>${icono}</span><span>${titulo}</span></div>
              <div class="popup-content">
                <div class="popup-row"><span class="popup-label">Evento:</span><span class="popup-value">${emoji} ${tipoIncidente}</span></div>
                <div class="popup-row"><span class="popup-label">Dirección:</span><span class="popup-value">${p.direccion || 'No registrada'}</span></div>
                <div class="popup-row"><span class="popup-label">Ubicación:</span><span class="popup-value">${p.barrio || ''} • Comuna ${p.comuna || 'N/A'}</span></div>
                <div class="popup-row"><span class="popup-label">Hora:</span><span class="popup-value">📅 ${p.fecha} ⏰ ${p.hora}</span></div>
                <div class="popup-row"><span class="popup-label">Gravedad:</span><span class="popup-value gravedad-destacada">${p.gravedad}</span></div>
              </div>
            </div>`;
          layer.bindPopup(popupHtml, { className: 'enhanced-popup' });
        }
      }).addTo(mainClusterGroup);
    }

<<<<<<< HEAD
    // Modal de incidentes (Lista)
    const modal = document.getElementById('incidentModal');
    const btnListIncidents = document.getElementById('btnListIncidents');
    if(btnListIncidents) {
      btnListIncidents.onclick = () => { modal.classList.add('active'); isModalOpen = true; refreshIncidentList(); };
    }
    document.getElementById('closeModalBtn').onclick = () => { modal.classList.remove('active'); isModalOpen = false; };
    
    function getAllIncidents() {
      return [...currentIncidents.features];
=======
    // Sistema de Control de Flujo de Interfaz Hidrometeorológica
    document.getElementById('rainBtn').onclick = function() {
      rainActive = !rainActive;
      this.classList.toggle('active', rainActive);
      rainLayerGroup.clearLayers();
      
      if (rainActive) {
        const rainIncidents = [];
        const baseCoords = [[6.251,-75.580],[6.268,-75.570],[6.230,-75.592],[6.245,-75.545],[6.210,-75.565]];
        const simCount = 10;
        
        for (let i = 0; i < simCount; i++) {
          const base = baseCoords[i % baseCoords.length];
          const lat = base[0] + (Math.random() - 0.5) * 0.01;
          const lng = base[1] + (Math.random() - 0.5) * 0.01;
          
          rainIncidents.push({
            type: 'Feature',
            geometry: { type: 'Point', coordinates: [lng, lat] },
            properties: {
              clase: 'Hidroplaneo / Acumulación de Agua', tipo: 'Climático', gravedad: 'Grave',
              direccion: 'Vía Estructural Metropolitana', barrio: 'Eje de Escorrentía', comuna: 'SIMM',
              fecha: dateString(), hora: timeString()
            }
          });
        }
        currentRainIncidents = rainIncidents;
        
        L.geoJSON({ type: 'FeatureCollection', features: rainIncidents }, {
          pointToLayer: (f, ll) => L.circleMarker(ll, { radius: 6, fillColor: '#2563eb', color: '#fff', weight: 1.5, fillOpacity: 0.8 })
        }).addTo(rainLayerGroup);
        
        congestionPercent = Math.min(98, congestionPercent + 25);
        avgSpeedKmh = Math.max(8, avgSpeedKmh - 10);
        document.getElementById('sFlood').textContent = 'Alto';
        document.getElementById('sFloodLabel').className = 'label danger';
        document.getElementById('rainLabel').textContent = 'Lluvia Activa 🌧️';
      } else {
        currentRainIncidents = [];
        congestionPercent = <?= $baseCongestion ?>;
        avgSpeedKmh = <?= $avgSpeed ?>;
        document.getElementById('sFlood').textContent = 'Bajo';
        document.getElementById('sFloodLabel').className = 'label ok';
        document.getElementById('rainLabel').textContent = 'Simular lluvia intensa';
      }
      
      document.getElementById('sCong').textContent = congestionPercent + '%';
      document.getElementById('sSpeed').textContent = avgSpeedKmh + ' km/h';
      document.getElementById('sCongHint').textContent = rainActive ? '+25% por precipitaciones' : 'Tiempo real';
      if(isModalOpen) refreshIncidentList();
    };

    // Control de Modales de Datos Estructurados
    const modal = document.getElementById('incidentModal');
    document.getElementById('btnListIncidents').onclick = () => { modal.classList.add('active'); isModalOpen = true; refreshIncidentList(); };
    document.getElementById('closeModalBtn').onclick = () => { modal.classList.remove('active'); isModalOpen = false; };
    
    function getAllIncidents() {
      let list = [...currentIncidents.features];
      if(rainActive) list = [...list, ...currentRainIncidents];
      return list;
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb
    }

    function refreshIncidentList() {
      const list = getAllIncidents();
      const container = document.getElementById('incidentListContainer');
      if(!list.length) { container.innerHTML = '<p class="text-center text-slate-400 py-6">No hay registros.</p>'; return; }
      
      container.innerHTML = list.map(i => {
        const p = i.properties;
        const gClass = p.gravedad.toLowerCase().includes('mortal') || p.gravedad.toLowerCase().includes('muerto') ? 'gravedad-mortal' : (p.gravedad.toLowerCase().includes('grave') ? 'gravedad-grave' : 'gravedad-leve');
        return `
          <div class="incident-card">
            <div class="flex justify-between items-center mb-2">
              <span class="font-bold text-sm">${p.clase}</span>
              <span class="gravedad-badge ${gClass}">${p.gravedad}</span>
            </div>
            <div class="text-xs text-slate-400 space-y-1">
              <div><i class="fa-solid fa-location-dot text-slate-500 mr-1"></i> ${p.direccion} (${p.barrio})</div>
              <div><i class="fa-solid fa-clock text-slate-500 mr-1"></i> ${p.fecha} a las ${p.hora}</div>
            </div>
            <button class="btn-view-map" onclick="flyToIncident(${i.geometry.coordinates[1]}, ${i.geometry.coordinates[0]})"><i class="fa-solid fa-eye"></i> Enfocar mapa</button>
          </div>`;
      }).join('');
    }

    function flyToIncident(lat, lng) {
      modal.classList.remove('active');
      isModalOpen = false;
      map.flyTo([lat, lng], 17, { duration: 1.5 });
    }

<<<<<<< HEAD
    // Filtros
    document.getElementById('alertAllBtn').onclick = () => loadIncidentsToMap(currentIncidents, false);
    document.getElementById('alertDeathsBtn').onclick = () => loadIncidentsToMap(currentIncidents, true);

    // Toggles (sidebar)
    const sb = document.getElementById('sidebar');
    document.getElementById('btnMenu').onclick = () => sb.classList.toggle('hidden');
    document.getElementById('closeSidebar').onclick = () => sb.classList.add('hidden');
    
    // Botón "Agente IA" – intenta abrir el chat de Botpress si existe, sino simplemente muestra el widget
    const btnChat = document.getElementById('btnChat');
    if(btnChat) {
      btnChat.onclick = () => {
        // Intenta abrir el webchat de Botpress (si está disponible)
        if(window.botpressWebChat && typeof window.botpressWebChat.sendEvent === 'function') {
          window.botpressWebChat.sendEvent({ type: 'show' });
        } else {
          // Si no hay API, simplemente redirige la atención al widget flotante (ya visible)
          console.log("Botpress webchat no disponible o aún cargando");
        }
      };
    }
=======
    // Filtros Rápidos de Consola Lateral
    document.getElementById('alertAllBtn').onclick = () => loadIncidentsToMap(currentIncidents, false);
    document.getElementById('alertDeathsBtn').onclick = () => loadIncidentsToMap(currentIncidents, true);

    // Toggles Nav Responsivo
    const sb = document.getElementById('sidebar');
    const ch = document.getElementById('chat');
    document.getElementById('btnMenu').onclick = () => { sb.classList.toggle('hidden'); if(window.innerWidth<640) ch.classList.remove('open'); };
    document.getElementById('closeSidebar').onclick = () => sb.classList.add('hidden');
    document.getElementById('btnChat').onclick = () => { ch.classList.toggle('open'); if(window.innerWidth<640) sb.classList.add('hidden'); };
    document.getElementById('closeChat').onclick = () => ch.classList.remove('open');

    // Motor conversacional del Agente IA Predictivo OS
    const msgs = document.getElementById('msgs');
    const input = document.getElementById('chatInput');
    
    function sendChat() {
      const txt = input.value.trim(); if(!txt) return;
      msgs.insertAdjacentHTML('beforeend', `<div class="msg user">${txt.replace(/</g,'&lt;')}</div>`);
      input.value = '';
      msgs.scrollTop = msgs.scrollHeight;
      
      setTimeout(() => {
        let resp = "Interrogación recibida. Monitoreo perimetral estable en el Valle de Aburrá.";
        const low = txt.toLowerCase();
        
        if (low.includes('accidente') || low.includes('cuántos') || low.includes('reporte')) {
          resp = `📊 Consola de MIMS reporta: ${totalIncidentsCount} incidentes totales activos. Segmentación crítica: ${criticalCount} fatalidades registradas.`;
        } else if (low.includes('trafico') || low.includes('congestión') || low.includes('velocidad')) {
          resp = `🚗 Estado de Malla Vial: Congestión promedio del ${congestionPercent}%. Velocidad media estimada de operación en arterias principales: ${avgSpeedKmh} km/h.`;
        } else if (low.includes('lluvia') || low.includes('clima')) {
          resp = rainActive ? "🌧️ Alerta por Precipitaciones Históricas activas. Cámaras del SIMM registran reducción crítica de fricción asfáltica en la Autopista Sur." : "☀️ Reporte hidrometeorológico estable. Malla asfáltica seca en las comunas bajo monitoreo.";
        }
        
        msgs.insertAdjacentHTML('beforeend', `<div class="msg bot">🚦 ${resp}</div>`);
        msgs.scrollTop = msgs.scrollHeight;
      }, 550);
    }
    document.getElementById('chatSend').onclick = sendChat;
    input.onkeydown = (e) => { if(e.key === 'Enter') sendChat(); };

    function dateString() { return new Date().toISOString().split('T')[0]; }
    function timeString() { return new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}); }
>>>>>>> de973c3c64ca69fbfc8cc06d37143c6990d9aafb

    window.onload = initMap;
  </script>
</body>
</html>
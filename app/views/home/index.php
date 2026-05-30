<?php
/**
 * RutaX · Colombia Movilidad OS
 * @version 6.0 - Heatmap rojo + ruta verde + PWA completa
 */
$APP_NAME = 'RutaX · Colombia Movilidad OS';

// -------------------------------------------------------------------
// Cargar incidentes desde GeoJSON o generar datos de ejemplo
// -------------------------------------------------------------------
$geojsonFile = __DIR__ . '/data/total_incidentes_colombia.geojson';
$geoJsonData = null;
$totalIncidents = 0;
$criticalCount = 0;

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

// Generar incidentes de ejemplo si no hay archivo
if (!$geoJsonData) {
    $ciudades = [
        ['nombre' => 'Bogotá', 'lat' => 4.7109, 'lng' => -74.0721, 'comuna' => 'Cundinamarca'],
        ['nombre' => 'Medellín', 'lat' => 6.2476, 'lng' => -75.5658, 'comuna' => 'Antioquia'],
        ['nombre' => 'Cali', 'lat' => 3.4516, 'lng' => -76.5320, 'comuna' => 'Valle del Cauca'],
        ['nombre' => 'Barranquilla', 'lat' => 10.9639, 'lng' => -74.7964, 'comuna' => 'Atlántico'],
        ['nombre' => 'Cartagena', 'lat' => 10.3910, 'lng' => -75.4794, 'comuna' => 'Bolívar'],
        ['nombre' => 'Bucaramanga', 'lat' => 7.1193, 'lng' => -73.1227, 'comuna' => 'Santander'],
        ['nombre' => 'Pereira', 'lat' => 4.8087, 'lng' => -75.6906, 'comuna' => 'Risaralda'],
        ['nombre' => 'Santa Marta', 'lat' => 11.2408, 'lng' => -74.1990, 'comuna' => 'Magdalena'],
        ['nombre' => 'Ibagué', 'lat' => 4.4389, 'lng' => -75.2322, 'comuna' => 'Tolima'],
        ['nombre' => 'Manizales', 'lat' => 5.0675, 'lng' => -75.5193, 'comuna' => 'Caldas'],
        ['nombre' => 'Neiva', 'lat' => 2.9275, 'lng' => -75.2819, 'comuna' => 'Huila'],
        ['nombre' => 'Villavicencio', 'lat' => 4.1429, 'lng' => -73.6266, 'comuna' => 'Meta'],
        ['nombre' => 'Cúcuta', 'lat' => 7.9019, 'lng' => -72.4965, 'comuna' => 'Norte de Santander'],
        ['nombre' => 'Pasto', 'lat' => 1.2136, 'lng' => -77.2811, 'comuna' => 'Nariño'],
        ['nombre' => 'Montería', 'lat' => 8.7490, 'lng' => -75.8838, 'comuna' => 'Córdoba'],
        ['nombre' => 'Sincelejo', 'lat' => 9.3047, 'lng' => -75.3978, 'comuna' => 'Sucre'],
        ['nombre' => 'Riohacha', 'lat' => 11.5444, 'lng' => -72.9078, 'comuna' => 'La Guajira'],
        ['nombre' => 'Quibdó', 'lat' => 5.6918, 'lng' => -76.6586, 'comuna' => 'Chocó'],
        ['nombre' => 'Tunja', 'lat' => 5.5325, 'lng' => -73.3675, 'comuna' => 'Boyacá'],
        ['nombre' => 'Armenia', 'lat' => 4.5339, 'lng' => -75.6811, 'comuna' => 'Quindío']
    ];
    $tipos = ['Choque', 'Atropello', 'Caída de moto', 'Daños materiales', 'Colisión múltiple', 'Obstrucción'];
    $gravedades = ['Leve', 'Grave', 'Mortal', 'Sin lesionados', 'Hospitalización'];
    $features = [];
    $numIncidents = rand(40, 60);
    for ($i = 0; $i < $numIncidents; $i++) {
        if (rand(1, 100) <= 70) {
            $city = $ciudades[array_rand($ciudades)];
            $lat = $city['lat'] + (mt_rand(-50, 50) / 1000);
            $lng = $city['lng'] + (mt_rand(-50, 50) / 1000);
            $comuna = $city['comuna'];
            $barrio = $city['nombre'];
        } else {
            $lat = mt_rand(-400, 1250) / 100;
            $lng = mt_rand(-7900, -6700) / 100;
            $comuna = 'Zona rural';
            $barrio = 'Carretera nacional';
        }
        $tipo = $tipos[array_rand($tipos)];
        $gravedad = $gravedades[array_rand($gravedades)];
        if ($gravedad === 'Mortal') $criticalCount++;
        $features[] = [
            'type' => 'Feature',
            'geometry' => ['type' => 'Point', 'coordinates' => [$lng, $lat]],
            'properties' => [
                'id' => $i, 'clase' => $tipo, 'tipo' => $tipo, 'gravedad' => $gravedad,
                'direccion' => 'Vía ' . $barrio . ' km ' . rand(1, 50),
                'barrio' => $barrio, 'comuna' => $comuna,
                'fecha' => date('Y-m-d'), 'hora' => date('H:i:s')
            ]
        ];
    }
    $totalIncidents = count($features);
    $geoJsonData = ['type' => 'FeatureCollection', 'features' => $features];
}

$baseCongestion = min(85, 20 + floor($totalIncidents / 2.5));
$avgSpeed = max(12, 45 - floor($baseCongestion / 2.2));

// Rutas de imágenes (ajusta según tu estructura)
$logoPath = '../../images/logo.png';
$faviconPath = '../../images/favico.png';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?php echo htmlspecialchars($APP_NAME); ?></title>
    
    <!-- PWA Meta -->
    <link rel="manifest" href="/transicontrol/manifest.json">
    <meta name="theme-color" content="#0F4C81">
    <meta name="apple-mobile-web-app-capable" content="yes">
    
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $faviconPath; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $faviconPath; ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $faviconPath; ?>">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    
    <script src="https://cdn.botpress.cloud/webchat/v3.6/inject.js"></script>
    <script src="https://files.bpcontent.cloud/2026/05/27/17/20260527172542-3Z3UA6G3.js" defer></script>
    
    <style>
        :root{
            --bg:#0b1220; --card:#111a2e; --border:#1f2a44;
            --fg:#e6edf7; --muted:#8a97b2;
            --primary:#0F4C81; --accent:#F9A825;
            --ok:#10b981; --warn:#f59e0b; --danger:#ef4444;
        }
        *{box-sizing:border-box}
        html,body{margin:0;height:100%;background:var(--bg);color:var(--fg);font-family:'Inter',sans-serif;overflow:hidden}
        #map{position:absolute;inset:0;z-index:0;background:#0b1220}
        
        .topbar{position:absolute;inset:1rem 1rem auto 1rem;z-index:20;display:flex;justify-content:space-between;align-items:flex-start;pointer-events:none}
        .pill{pointer-events:auto;display:inline-flex;align-items:center;gap:.5rem;padding:.65rem 1rem;border-radius:.85rem;
          background:rgba(17,26,46,.88);border:1px solid var(--border);backdrop-filter:blur(10px);
          color:var(--fg);font-weight:600;font-size:.9rem;cursor:pointer;box-shadow:0 8px 24px rgba(0,0,0,.35);transition:.2s;text-decoration:none}
        .pill:hover{background:rgba(17,26,46,1)}
        @media (max-width: 640px){.pill span{display:none}.app-title-text{display:none}}
        .live{display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--ok);animation:pulse 1.8s infinite}
        @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(16,185,129,.6)}70%{box-shadow:0 0 0 10px rgba(16,185,129,0)}100%{box-shadow:0 0 0 0 rgba(16,185,129,0)}}
        
        .circular-logo{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:var(--primary);border:2px solid var(--accent);overflow:hidden}
        .circular-logo img{width:100%;height:100%;object-fit:cover}
        .sidebar-logo .circular-logo{width:44px;height:44px}
        
        .panel{position:absolute;top:0;height:100%;background:rgba(17,26,46,.96);backdrop-filter:blur(14px);
          border-color:var(--border);box-shadow:0 20px 60px rgba(0,0,0,.5);z-index:30;
          display:flex;flex-direction:column;transition:transform .45s cubic-bezier(.22,.61,.36,1);width:100%;}
        @media (min-width:640px){.sidebar{width:340px}}
        .sidebar{left:0;border-right:1px solid var(--border);transform:translateX(0)}
        .sidebar.hidden{transform:translateX(-100%)}
        .panel header{display:flex;justify-content:space-between;align-items:center;padding:1.1rem 1.2rem;border-bottom:1px solid var(--border)}
        .panel header h3{margin:0;font-size:1.05rem;font-weight:700}
        .panel header small{color:var(--muted);font-size:.72rem}
        .icon-btn{background:transparent;border:none;color:var(--fg);cursor:pointer;padding:.4rem;border-radius:.5rem}
        .icon-btn:hover{background:rgba(255,255,255,.08)}
        .scroll{overflow-y:auto;flex:1}
        .stats{display:grid;grid-template-columns:1fr 1fr;gap:.65rem;padding:0 1rem}
        .stat{background:rgba(11,18,32,.65);border:1px solid var(--border);border-radius:.85rem;padding:.75rem}
        .stat .label{display:flex;align-items:center;gap:.4rem;font-size:.72rem;color:var(--muted);font-weight:500}
        .stat .value{font-size:1.35rem;font-weight:800;margin-top:.2rem}
        .stat .hint{font-size:.68rem;color:var(--muted);margin-top:.1rem}
        .ok{color:var(--ok)} .warn{color:var(--warn)} .danger{color:var(--danger)}
        
        nav.menu{padding:.5rem .75rem 1.25rem}
        nav.menu .title{padding:.5rem .5rem;font-size:.68rem;font-weight:600;color:var(--muted);text-transform:uppercase}
        nav.menu a{display:flex;align-items:center;gap:.7rem;padding:.65rem .75rem;border-radius:.55rem;color:#cbd5e1;text-decoration:none;font-size:.88rem;font-weight:500;transition:.15s}
        nav.menu a i{color:var(--accent);width:18px;text-align:center}
        nav.menu a:hover{background:rgba(255,255,255,.06);color:#fff}
        .panel footer{padding:.85rem 1rem;border-top:1px solid var(--border);font-size:.7rem;color:var(--muted)}
        
        .weather-card, .air-card, .sun-card, .route-card, .forecast-card{background:linear-gradient(135deg,rgba(15,76,129,0.2),rgba(0,0,0,0.2));border:1px solid var(--border);border-radius:1rem;margin:1rem 1rem 0 1rem;padding:.75rem;backdrop-filter:blur(4px)}
        .weather-temp{font-size:2rem;font-weight:800;line-height:1}
        .weather-desc{font-size:.75rem;text-transform:capitalize}
        .weather-update{font-size:.6rem;color:var(--muted);text-align:right;margin-top:.5rem}
        .air-quality-index{font-size:1.8rem;font-weight:800;line-height:1}
        .sun-icon{font-size:1.8rem}
        .forecast-item{display:flex;justify-content:space-between;font-size:.7rem;margin-bottom:.3rem}
        
        .alert-btn{position:relative;overflow:hidden;transition:all .3s ease}
        .alert-btn:hover{transform:translateY(-4px);box-shadow:0 0 20px rgba(239,68,68,0.4)}
        .death-alert{animation:pulse-red 2s infinite}
        @keyframes pulse-red{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,0.6)}70%{box-shadow:0 0 0 15px rgba(239,68,68,0)}}
        
        .enhanced-popup .leaflet-popup-content-wrapper{background:rgba(11,18,32,0.95);backdrop-filter:blur(8px);border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.5);padding:0}
        .enhanced-popup .leaflet-popup-content{margin:0;width:280px!important}
        .custom-popup{color:#fff}
        .popup-header{display:flex;align-items:center;gap:8px;padding:12px 16px;font-weight:800;border-bottom:1px solid rgba(255,255,255,0.1)}
        .popup-content{padding:12px 16px}
        .popup-row{display:flex;justify-content:space-between;font-size:.78rem;border-bottom:1px dashed rgba(255,255,255,0.08);padding-bottom:4px}
        .gravedad-destacada{font-weight:800;text-transform:uppercase;padding:2px 6px;border-radius:10px}
        .popup-mortal .popup-header{background:#b91c1c}
        .popup-grave .popup-header{background:#ea580c}
        .popup-leve .popup-header{background:#1e3a8a}
        
        .modal-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;visibility:hidden;opacity:0;transition:.2s}
        .modal-overlay.active{visibility:visible;opacity:1}
        .modal-container{background:rgba(17,26,46,0.98);border-radius:1.5rem;border:1px solid var(--border);width:90%;max-width:650px;max-height:85vh;display:flex;flex-direction:column}
        .modal-header{display:flex;justify-content:space-between;padding:1rem 1.5rem;border-bottom:1px solid var(--border)}
        .incident-list{flex:1;overflow-y:auto;padding:1rem}
        .incident-card{background:rgba(11,18,32,0.5);border:1px solid var(--border);border-radius:1rem;padding:.8rem 1rem;margin-bottom:.6rem}
        .gravedad-badge{font-size:.7rem;padding:.2rem .6rem;border-radius:20px;font-weight:700}
        .gravedad-mortal{background:rgba(239,68,68,0.15);color:#ff6b6b}
        .gravedad-grave{background:rgba(249,115,22,0.15);color:#ffb347}
        .gravedad-leve{background:rgba(234,179,8,0.15);color:#fde047}
        .btn-view-map{background:var(--primary);color:white;padding:.35rem .85rem;border-radius:.5rem;font-size:.75rem;cursor:pointer;display:inline-block}
        .sidebar-logo{display:flex;align-items:center;gap:.5rem}
        
        .bar-stats-section{margin:1rem;background:rgba(11,18,32,0.4);border-radius:1rem;border:1px solid var(--border);padding:.8rem}
        .bar-stats-title{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--accent);margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem}
        .bar-item{margin-bottom:.7rem}
        .bar-label{display:flex;justify-content:space-between;font-size:.7rem;font-weight:500;margin-bottom:.2rem}
        .bar-bg{background:rgba(255,255,255,0.08);border-radius:20px;overflow:hidden;height:8px;width:100%}
        .bar-fill{height:100%;width:0%;border-radius:20px;transition:width .6s}
        .fill-leve{background:linear-gradient(90deg,#10b981,#34d399)}
        .fill-grave{background:linear-gradient(90deg,#f59e0b,#fbbf24)}
        .fill-mortal{background:linear-gradient(90deg,#ef4444,#f97316)}
        .fill-comuna{background:linear-gradient(90deg,#0F4C81,#3b82f6)}
        .comuna-rank{font-size:.7rem;font-family:monospace;color:var(--accent)}
        
        .btn-route-active{background:#f97316 !important;border-color:#f97316 !important;color:white !important}
        .traffic-bajo{color:#10b981}
        .traffic-medio{color:#f59e0b}
        .traffic-alto{color:#ef4444}
    </style>
</head>
<body>

<div id="map"></div>

<div class="topbar">
    <button class="pill" id="btnMenu"><i class="fa-solid fa-bars"></i><span>Menú</span></button>
    <div class="pill" style="cursor:default; gap:0.75rem;">
        <div class="circular-logo"><img src="<?php echo $logoPath; ?>" alt="RutaX Logo"></div>
        <span class="live"></span>
        <span class="app-title-text"><?php echo htmlspecialchars($APP_NAME); ?></span>
        <span class="sm:hidden">RutaX</span>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <button class="pill" id="zoomInBtn"><i class="fa-solid fa-plus"></i><span>Acercar</span></button>
        <button class="pill" id="zoomOutBtn"><i class="fa-solid fa-minus"></i><span>Alejar</span></button>
        <button class="pill" id="btnListIncidents"><i class="fa-solid fa-list"></i><span>Incidentes</span></button>
        <button class="pill" id="btnGeolocate"><i class="fa-solid fa-location-dot"></i><span>Mi ubicación</span></button>
        <button class="pill" id="btnSafeRoute"><i class="fa-solid fa-route"></i><span>Ruta segura</span></button>
        <button class="pill" id="toggleHeatmapBtn"><i class="fa-solid fa-fire"></i><span>Heatmap</span></button>
    </div>
</div>

<div id="incidentModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="flex items-center gap-2"><i class="fa-solid fa-car-crash text-amber-500"></i> Lista de Incidentes Activos</h3>
            <button id="closeModalBtn"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="incidentListContainer" class="incident-list"></div>
    </div>
</div>

<aside class="panel sidebar hidden" id="sidebar">
    <header>
        <div class="sidebar-logo">
            <div class="circular-logo"><img src="<?php echo $logoPath; ?>" alt="Logo"></div>
            <div><h3>RutaX</h3><small>Colombia Movilidad OS</small></div>
        </div>
        <button class="icon-btn" id="closeSidebar"><i class="fa-solid fa-xmark"></i></button>
    </header>
    <div class="scroll">
        <div class="weather-card" id="weatherCard">
            <div class="flex justify-between"><div><div class="weather-temp" id="weatherTemp">--°C</div><div id="weatherDesc">Cargando...</div></div><div class="text-4xl" id="weatherIcon"><i class="fa-solid fa-cloud-sun"></i></div></div>
            <div class="flex justify-between mt-2 text-xs"><span><i class="fa-solid fa-droplet"></i> <span id="weatherHumidity">--</span>%</span><span><i class="fa-solid fa-wind"></i> <span id="weatherWind">--</span> km/h</span><span><i class="fa-solid fa-temperature-low"></i> <span id="weatherFeels">--</span>°C</span></div>
            <div class="flex justify-between mt-1 text-xs"><span><i class="fa-solid fa-gauge"></i> Presión: <span id="weatherPressure">--</span> hPa</span><span><i class="fa-regular fa-eye"></i> Visibilidad: <span id="weatherVisibility">--</span> km</span><span><i class="fa-solid fa-sun"></i> UV: <span id="uvIndex">--</span></span></div>
            <div class="weather-update" id="weatherUpdate"></div>
        </div>

        <div class="forecast-card">
            <div class="text-xs uppercase tracking-wide text-slate-400"><i class="fa-solid fa-calendar-week"></i> Pronóstico 3 días (Bogotá)</div>
            <div id="forecastContainer" class="mt-1 space-y-1"></div>
        </div>

        <div class="air-card" id="airCard">
            <div class="flex justify-between"><div><div class="text-xs uppercase">Calidad del Aire</div><div class="air-quality-index" id="aqiValue">--</div><div class="text-xs" id="aqiCategory">Cargando...</div></div><div class="text-3xl" id="aqiIcon"><i class="fa-solid fa-leaf"></i></div></div>
            <div class="grid grid-cols-3 gap-1 mt-2 text-center text-[10px]"><div><span id="pm25">--</span> PM2.5</div><div><span id="pm10">--</span> PM10</div><div><span id="co2">--</span> CO₂</div></div>
            <div class="weather-update" id="airUpdate"></div>
        </div>

        <div class="sun-card" id="sunCard">
            <div class="flex justify-between"><div><div class="text-xs uppercase">Iluminación solar</div><div class="text-base font-bold" id="dayPeriod">--</div><div class="text-[11px]" id="sunTimes">Salida: --:-- / Puesta: --:--</div></div><div class="sun-icon text-3xl" id="sunIcon"><i class="fa-regular fa-sun"></i></div></div>
            <div class="weather-update" id="sunUpdate"></div>
        </div>

        <div class="route-card" id="routeCard">
            <div class="flex justify-between"><div><div class="text-xs uppercase">Ruta sugerida</div><div class="text-sm font-bold" id="routeDistance">-- km</div><div class="text-xs" id="routeDuration">-- min</div></div><div class="text-2xl"><i class="fa-solid fa-route"></i></div></div>
            <div class="mt-2 text-xs" id="routeTrafficRisk"></div>
            <div class="mt-1 text-xs text-slate-400" id="routeWarning"></div>
            <div class="weather-update" id="routeUpdate"></div>
        </div>

        <div class="p-4 space-y-3">
            <div class="text-[10px] uppercase font-bold text-slate-400"><i class="fa-solid fa-bell"></i> Filtros</div>
            <button id="alertAllBtn" class="alert-btn w-full bg-slate-800 border border-slate-700 p-3 rounded-xl text-left flex gap-2"><i class="fa-solid fa-car-crash text-amber-500"></i><div><div class="text-sm">Todos los Incidentes</div><div class="text-xs opacity-60" id="totalIncidentsLabel"><?php echo $totalIncidents; ?> registros</div></div></button>
            <button id="alertDeathsBtn" class="alert-btn death-alert w-full bg-gradient-to-r from-red-950 to-red-900 border border-red-700 p-3 rounded-xl text-left flex gap-2"><i class="fa-solid fa-skull-crossbones text-red-500"></i><div><div class="text-sm">Casos Críticos / Mortales</div><div class="text-xs text-red-300 opacity-80" id="criticalIncidentsLabel"><?php echo $criticalCount; ?> alertas</div></div></button>
        </div>

        <div class="stats">
            <div class="stat"><div class="label warn"><i class="fa-solid fa-chart-line"></i>Congestión</div><div class="value" id="sCong"><?php echo $baseCongestion; ?>%</div></div>
            <div class="stat"><div class="label danger"><i class="fa-solid fa-triangle-exclamation"></i>Puntos críticos</div><div class="value" id="sCrit"><?php echo $criticalCount; ?></div></div>
            <div class="stat"><div class="label ok"><i class="fa-solid fa-droplet"></i>Inundación</div><div class="value">Bajo</div></div>
            <div class="stat"><div class="label ok"><i class="fa-solid fa-gauge-high"></i>Velocidad</div><div class="value" id="sSpeed"><?php echo $avgSpeed; ?> km/h</div></div>
        </div>

        <div class="bar-stats-section"><div class="bar-stats-title"><i class="fa-solid fa-chart-simple"></i> Incidentes por gravedad</div><div id="severityBarsContainer"></div></div>
        <div class="bar-stats-section"><div class="bar-stats-title"><i class="fa-solid fa-ranking-star"></i> Departamentos con más incidentes</div><div id="comunaBarsContainer"></div></div>

        <nav class="menu">
            <div class="title">Navegación principal</div>
            <a href="Infracciones.php"><i class="fa-solid fa-file-lines"></i> Infracciones</a>
            <a href="Agentes.php"><i class="fa-solid fa-shield-halved"></i> Agentes</a>
            <a href="Abogados.php"><i class="fa-solid fa-gavel"></i> Abogados</a>
            <div class="title mt-2">Autenticación</div>
            <a href="../auth/login.php"><i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión</a>
            <a href="../auth/google.php"><i class="fa-brands fa-google"></i> Google Auth</a>
        </nav>
    </div>
    <footer>RutaX · TransiControl · SIMIT · <span id="footerTotalIncidents"><?php echo $totalIncidents; ?></span> incidentes en Colombia</footer>
</aside>

<script>
    // -------------------- VARIABLES GLOBALES --------------------
    let map = null, mainClusterGroup = null, eventSource = null, allIncidents = [];
    let userMarker = null, userCircle = null, currentRouteLayer = null, destinationMarker = null;
    let selectingDestination = false, heatmapLayer = null, heatmapEnabled = true;
    let totalIncidentsCount = <?php echo $totalIncidents; ?>, criticalCount = <?php echo $criticalCount; ?>;
    let congestionPercent = <?php echo $baseCongestion; ?>, avgSpeedKmh = <?php echo $avgSpeed; ?>, isModalOpen = false;
    const COLOMBIA_CENTER = { lat: 4.5709, lng: -74.2973 };
    const OR_API_KEY = '5b3ce3597851110001cf6248c299c86a284a4e28bb1e4f3efb6cee29';
    const ORS_BASE_URL = 'https://api.openrouteservice.org/v2/directions/driving-car';
    let geocodeEnabled = true;
    let wasHeatmapEnabledByRoute = false;
    const BOGOTA = { lat: 4.7109, lng: -74.0721 };

    // -------------------- MAPA --------------------
    function initMap() {
        map = L.map('map', { zoomControl: false }).setView([COLOMBIA_CENTER.lat, COLOMBIA_CENTER.lng], 6);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© OpenStreetMap · CARTO', maxZoom: 19 }).addTo(map);
        mainClusterGroup = L.markerClusterGroup({ maxClusterRadius: 50, spiderfyOnMaxZoom: true });
        map.addLayer(mainClusterGroup);
        connectToEventStream();
        map.on('click', onMapClick);
    }

    async function reverseGeocode(lat, lng) {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
            const data = await res.json();
            let addr = data.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
            if (addr.length > 60) addr = addr.substring(0,57)+'...';
            L.popup().setLatLng([lat,lng]).setContent(`<strong>Ubicación</strong><br>${addr}`).openOn(map);
        } catch(e) { console.error(e); }
    }

    function onMapClick(e) {
        if (selectingDestination) {
            suggestSafeRoute(e.latlng.lat, e.latlng.lng);
            deactivateRouteSelection();
        } else if (geocodeEnabled) reverseGeocode(e.latlng.lat, e.latlng.lng);
    }

    // -------------------- HEATMAP (rojo) --------------------
    function updateHeatmap() {
        if (heatmapLayer) map.removeLayer(heatmapLayer);
        if (!heatmapEnabled) return;
        const points = allIncidents.map(inc => {
            const [lng, lat] = inc.geometry.coordinates;
            const grav = (inc.properties.gravedad || '').toUpperCase();
            let intensity = 0.5;
            if (grav.includes('MUERTO') || grav.includes('FATAL') || grav.includes('DECESO')) intensity = 1.0;
            else if (grav.includes('GRAVE') || grav.includes('HOSPITAL')) intensity = 0.8;
            else if (grav.includes('LEVE')) intensity = 0.4;
            else intensity = 0.3;
            return [lat, lng, intensity];
        });
        if (points.length) {
            heatmapLayer = L.heatLayer(points, {
                radius: 25, blur: 15, maxZoom: 17, minOpacity: 0.4,
                gradient: { 0.2: '#f97316', 0.5: '#ef4444', 0.8: '#b91c1c', 1.0: '#7f1d1d' }
            }).addTo(map);
        }
    }

    function toggleHeatmap() {
        heatmapEnabled = !heatmapEnabled;
        const btn = document.getElementById('toggleHeatmapBtn');
        if (heatmapEnabled) { updateHeatmap(); btn.style.background = 'rgba(17,26,46,0.9)'; btn.style.borderColor = 'var(--accent)'; }
        else { if (heatmapLayer) map.removeLayer(heatmapLayer); btn.style.background = 'rgba(239,68,68,0.5)'; btn.style.borderColor = '#ef4444'; }
    }

    // -------------------- RUTA SEGURA --------------------
    function activateRouteSelection() {
        selectingDestination = true;
        geocodeEnabled = false;
        if (!heatmapEnabled) {
            wasHeatmapEnabledByRoute = true;
            heatmapEnabled = true;
            updateHeatmap();
            document.getElementById('toggleHeatmapBtn').style.background = 'rgba(17,26,46,0.9)';
            document.getElementById('toggleHeatmapBtn').style.borderColor = 'var(--accent)';
        }
        document.getElementById('btnSafeRoute').classList.add('btn-route-active');
        document.getElementById('routeUpdate').innerHTML = 'Haz clic en el mapa para seleccionar destino (zonas rojas = alta congestión)';
        if (currentRouteLayer) map.removeLayer(currentRouteLayer);
        if (destinationMarker) map.removeLayer(destinationMarker);
        currentRouteLayer = null; destinationMarker = null;
        document.getElementById('routeDistance').innerHTML = '-- km';
        document.getElementById('routeDuration').innerHTML = '-- min';
        document.getElementById('routeWarning').innerHTML = '';
        document.getElementById('routeTrafficRisk').innerHTML = '';
    }

    function deactivateRouteSelection() {
        selectingDestination = false;
        geocodeEnabled = true;
        if (wasHeatmapEnabledByRoute) {
            heatmapEnabled = false;
            if (heatmapLayer) map.removeLayer(heatmapLayer);
            document.getElementById('toggleHeatmapBtn').style.background = 'rgba(239,68,68,0.5)';
            document.getElementById('toggleHeatmapBtn').style.borderColor = '#ef4444';
            wasHeatmapEnabledByRoute = false;
        }
        document.getElementById('btnSafeRoute').classList.remove('btn-route-active');
    }

    function getDistance(lat1,lng1,lat2,lng2) {
        const R=6371000, dLat=(lat2-lat1)*Math.PI/180, dLng=(lng2-lng1)*Math.PI/180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLng/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function calculateTrafficRisk(routeLatLngs) {
        let totalWeight = 0;
        for (const inc of allIncidents) {
            const [lng,lat] = inc.geometry.coordinates;
            let minDist = Infinity;
            for (let i=0; i<routeLatLngs.length; i++) {
                const d = getDistance(lat,lng, routeLatLngs[i][0], routeLatLngs[i][1]);
                if (d < minDist) minDist = d;
            }
            if (minDist <= 150) {
                const grav = (inc.properties.gravedad || '').toUpperCase();
                let w = 1;
                if (grav.includes('MUERTO')||grav.includes('FATAL')||grav.includes('DECESO')) w=3;
                else if (grav.includes('GRAVE')||grav.includes('HOSPITAL')) w=2;
                totalWeight += w;
            }
        }
        if (totalWeight===0) return { level:'Bajo', class:'traffic-bajo', message:'Tráfico bajo / Riesgo mínimo' };
        if (totalWeight<=5) return { level:'Medio', class:'traffic-medio', message:'Tráfico moderado - Precaución' };
        return { level:'Alto', class:'traffic-alto', message:'Alta congestión / Riesgo elevado. Evita esta ruta.' };
    }

    async function suggestSafeRoute(destLat, destLng) {
        let originLat, originLng;
        if (userMarker) { const ll = userMarker.getLatLng(); originLat = ll.lat; originLng = ll.lng; }
        else { originLat = COLOMBIA_CENTER.lat; originLng = COLOMBIA_CENTER.lng; }
        const url = `${ORS_BASE_URL}?api_key=${OR_API_KEY}&start=${originLng},${originLat}&end=${destLng},${destLat}`;
        try {
            const resp = await fetch(url);
            const data = await resp.json();
            if (data.features && data.features.length) {
                const geom = data.features[0].geometry.coordinates;
                const dist = data.features[0].properties.summary.distance/1000;
                const dura = data.features[0].properties.summary.duration/60;
                const latlngs = geom.map(c=>[c[1],c[0]]);
                if (currentRouteLayer) map.removeLayer(currentRouteLayer);
                currentRouteLayer = L.polyline(latlngs, { color: '#10b981', weight: 6, opacity: 0.9 }).addTo(map);
                map.fitBounds(currentRouteLayer.getBounds());
                if (destinationMarker) map.removeLayer(destinationMarker);
                const destIcon = L.divIcon({ html: '<i class="fa-solid fa-flag-checkered" style="color:#10b981; font-size:28px;"></i>', iconSize:[28,28] });
                destinationMarker = L.marker([destLat,destLng], { icon: destIcon }).addTo(map).bindPopup('Destino').openPopup();
                document.getElementById('routeDistance').innerHTML = dist.toFixed(2)+' km';
                document.getElementById('routeDuration').innerHTML = Math.round(dura)+' min';
                const traffic = calculateTrafficRisk(latlngs);
                document.getElementById('routeTrafficRisk').innerHTML = `<i class="fa-solid fa-car"></i> Tráfico/riesgo: <span class="${traffic.class}">${traffic.level}</span><br><span class="text-xs">${traffic.message}</span>`;
                let warnings = [];
                for (const inc of allIncidents) {
                    const [lng,lat] = inc.geometry.coordinates;
                    let minD = Infinity;
                    for (let i=0; i<latlngs.length; i++) {
                        const d = getDistance(lat,lng, latlngs[i][0], latlngs[i][1]);
                        if (d<minD) minD=d;
                    }
                    if (minD<=200) warnings.push(`${inc.properties.gravedad||'Incidente'} a ${Math.round(minD)}m`);
                }
                document.getElementById('routeWarning').innerHTML = warnings.length ? '<i class="fa-solid fa-triangle-exclamation"></i> '+warnings.slice(0,3).join('; ') : '<i class="fa-solid fa-check-circle"></i> Sin incidentes cercanos (radio 200m)';
                document.getElementById('routeUpdate').innerHTML = `Actualizado: ${new Date().toLocaleTimeString()}`;
            } else throw new Error('Ruta no encontrada');
        } catch(e) { console.error(e); document.getElementById('routeWarning').innerHTML = '<i class="fa-solid fa-exclamation-triangle"></i> Error al calcular la ruta'; }
    }

    // -------------------- APIs externas (clima, aire, sol) --------------------
    async function fetchWeather() {
        try {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${BOGOTA.lat}&longitude=${BOGOTA.lng}&current=temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m,pressure_msl,visibility,uv_index&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max&timezone=auto`;
            const resp = await fetch(url);
            const data = await resp.json();
            if (data?.current) {
                const code = data.current.weather_code;
                let icon='fa-cloud-sun', desc='';
                if (code===0) { icon='fa-sun'; desc='Despejado'; }
                else if (code===1||code===2) { icon='fa-cloud-sun'; desc='Parcialmente nublado'; }
                else if (code===3) { icon='fa-cloud'; desc='Nublado'; }
                else if (code>=45&&code<=48) { icon='fa-smog'; desc='Niebla'; }
                else if (code>=51&&code<=55) { icon='fa-cloud-rain'; desc='Llovizna'; }
                else if (code>=61&&code<=65) { icon='fa-cloud-showers-heavy'; desc='Lluvia'; }
                else if (code>=95&&code<=99) { icon='fa-cloud-bolt'; desc='Tormenta'; }
                else { icon='fa-cloud'; desc='Variable'; }
                document.getElementById('weatherTemp').innerHTML = `${Math.round(data.current.temperature_2m)}°C`;
                document.getElementById('weatherDesc').innerHTML = desc;
                document.getElementById('weatherHumidity').innerHTML = data.current.relative_humidity_2m;
                document.getElementById('weatherWind').innerHTML = Math.round(data.current.wind_speed_10m);
                document.getElementById('weatherFeels').innerHTML = Math.round(data.current.apparent_temperature);
                document.getElementById('weatherPressure').innerHTML = data.current.pressure_msl;
                document.getElementById('weatherVisibility').innerHTML = (data.current.visibility/1000).toFixed(1);
                document.getElementById('uvIndex').innerHTML = data.current.uv_index;
                document.getElementById('weatherIcon').innerHTML = `<i class="fa-solid ${icon}"></i>`;
                document.getElementById('weatherUpdate').innerHTML = `Actualizado: ${new Date().toLocaleTimeString()}`;
            }
            if (data?.daily) {
                let html = '';
                for (let i=0;i<3;i++) {
                    const day = data.daily.time[i].slice(5);
                    const max = data.daily.temperature_2m_max[i];
                    const min = data.daily.temperature_2m_min[i];
                    const rain = data.daily.precipitation_probability_max[i];
                    let ic = (data.daily.weather_code[i]===0)?'☀️':(data.daily.weather_code[i]<=2)?'⛅':(data.daily.weather_code[i]>=61)?'🌧️':'☁️';
                    html += `<div class="forecast-item"><span>${ic} ${day}</span><span>${Math.round(min)}°/${Math.round(max)}°</span><span>💧${rain}%</span></div>`;
                }
                document.getElementById('forecastContainer').innerHTML = html;
            }
        } catch(e) { console.error(e); }
    }

    async function fetchAirQuality() {
        try {
            const url = `https://air-quality-api.open-meteo.com/v1/air-quality?latitude=${BOGOTA.lat}&longitude=${BOGOTA.lng}&current=us_aqi,pm10,pm2_5,carbon_monoxide&timezone=auto`;
            const resp = await fetch(url);
            const data = await resp.json();
            if (data?.current) {
                const aqi = data.current.us_aqi;
                let cat,ico,col;
                if (aqi<=50) { cat='Bueno'; ico='fa-smile'; col='#10b981'; }
                else if (aqi<=100) { cat='Moderado'; ico='fa-meh'; col='#f59e0b'; }
                else if (aqi<=150) { cat='Insalubre (sensible)'; ico='fa-mask'; col='#f97316'; }
                else if (aqi<=200) { cat='Insalubre'; ico='fa-skull-crossbones'; col='#ef4444'; }
                else { cat='Muy insalubre'; ico='fa-biohazard'; col='#b91c1c'; }
                document.getElementById('aqiValue').innerHTML = aqi;
                document.getElementById('aqiCategory').innerHTML = cat;
                document.getElementById('aqiIcon').innerHTML = `<i class="fa-solid ${ico}" style="color:${col}"></i>`;
                document.getElementById('pm25').innerHTML = Math.round(data.current.pm2_5);
                document.getElementById('pm10').innerHTML = Math.round(data.current.pm10);
                document.getElementById('co2').innerHTML = Math.round(data.current.carbon_monoxide);
                document.getElementById('airUpdate').innerHTML = `Actualizado: ${new Date().toLocaleTimeString()}`;
            }
        } catch(e) { console.error(e); }
    }

    async function fetchSunriseSunset() {
        try {
            const today = new Date().toISOString().split('T')[0];
            const url = `https://api.sunrise-sunset.org/json?lat=${BOGOTA.lat}&lng=${BOGOTA.lng}&date=${today}&formatted=0`;
            const resp = await fetch(url);
            const data = await resp.json();
            if (data.status==='OK') {
                const sunrise = new Date(data.results.sunrise);
                const sunset = new Date(data.results.sunset);
                const now = new Date();
                const isDay = now >= sunrise && now <= sunset;
                document.getElementById('dayPeriod').innerHTML = isDay ? 'Día (alta visibilidad)' : 'Noche (conducción con precaución)';
                document.getElementById('sunTimes').innerHTML = `Salida: ${sunrise.toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'})} / Puesta: ${sunset.toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'})}`;
                document.getElementById('sunIcon').innerHTML = `<i class="fa-regular ${isDay ? 'fa-sun' : 'fa-moon'}"></i>`;
                document.getElementById('sunUpdate').innerHTML = `Actualizado: ${now.toLocaleTimeString()}`;
            }
        } catch(e) { console.error(e); }
    }

    // -------------------- INCIDENTES SSE --------------------
    function connectToEventStream() {
        if (eventSource) eventSource.close();
        eventSource = new EventSource('stream_incidents.php');
        eventSource.onmessage = e => { try { handleNewIncident(JSON.parse(e.data)); } catch(ex){} };
        eventSource.onerror = () => { eventSource.close(); setTimeout(connectToEventStream,5000); };
    }

    function handleNewIncident(inc) {
        allIncidents.push(inc);
        totalIncidentsCount++;
        const g = (inc.properties.gravedad||'').toUpperCase();
        if (g.includes('MUERTO')||g.includes('FATAL')||g.includes('DECESO')) criticalCount++;
        congestionPercent = Math.min(85,20+Math.floor(totalIncidentsCount/2.5));
        avgSpeedKmh = Math.max(12,45-Math.floor(congestionPercent/2.2));
        document.getElementById('totalIncidentsLabel').innerHTML = totalIncidentsCount+' registros';
        document.getElementById('criticalIncidentsLabel').innerHTML = criticalCount+' alertas';
        document.getElementById('footerTotalIncidents').innerHTML = totalIncidentsCount;
        document.getElementById('sCrit').innerHTML = criticalCount;
        document.getElementById('sCong').innerHTML = congestionPercent+'%';
        document.getElementById('sSpeed').innerHTML = avgSpeedKmh+' km/h';
        addIncidentToMap(inc);
        if (isModalOpen) refreshIncidentList();
        updateBarStats();
        updateHeatmap();
    }

    function addIncidentToMap(inc) {
        const [lng,lat] = inc.geometry.coordinates;
        const props = inc.properties;
        let color = '#3b82f6';
        const g = (props.gravedad||'').toUpperCase();
        if (g.includes('MUERTO')||g.includes('FATAL')||g.includes('DECESO')) color='#ef4444';
        else if (g.includes('GRAVE')||g.includes('HOSPITAL')) color='#f97316';
        else if (g.includes('LEVE')) color='#eab308';
        const marker = L.circleMarker([lat,lng], { radius:8, fillColor:color, color:'#0b1220', weight:1.5, fillOpacity:0.9 });
        let nivel='popup-leve', titulo='INCIDENTE REGULAR', icono='<i class="fas fa-car"></i>';
        if (g.includes('MUERTO')||g.includes('FATAL')) { nivel='popup-mortal'; titulo='CASO FATAL'; icono='<i class="fas fa-skull"></i>'; }
        else if (g.includes('GRAVE')) { nivel='popup-grave'; titulo='ALERTA CRÍTICA'; icono='<i class="fas fa-exclamation-triangle"></i>'; }
        let tipoIcono = '<i class="fas fa-car-crash"></i>';
        const tipo = props.clase||props.tipo||'Incidente';
        if (tipo.toLowerCase().includes('moto')) tipoIcono='<i class="fas fa-motorcycle"></i>';
        else if (tipo.toLowerCase().includes('atropello')) tipoIcono='<i class="fas fa-person-walking"></i>';
        const popupHtml = `<div class="custom-popup ${nivel}"><div class="popup-header">${icono} ${titulo}</div><div class="popup-content"><div class="popup-row"><span>Evento:</span><span>${tipoIcono} ${tipo}</span></div><div class="popup-row"><span>Dirección:</span><span>${props.direccion||'N/R'}</span></div><div class="popup-row"><span>Ubicación:</span><span>${props.barrio||''} • ${props.comuna||'N/A'}</span></div><div class="popup-row"><span>Hora:</span><span>${props.fecha} ${props.hora}</span></div><div class="popup-row"><span>Gravedad:</span><span class="gravedad-destacada">${props.gravedad}</span></div></div></div>`;
        marker.bindPopup(popupHtml, { className:'enhanced-popup' });
        mainClusterGroup.addLayer(marker);
    }

    // -------------------- ESTADÍSTICAS Y BARRAS --------------------
    function computeSeverityStats() {
        let leves=0,graves=0,mortales=0;
        allIncidents.forEach(i=>{
            const g=(i.properties.gravedad||'').toUpperCase();
            if (g.includes('MUERTO')||g.includes('FATAL')||g.includes('DECESO')) mortales++;
            else if (g.includes('GRAVE')||g.includes('HOSPITAL')) graves++;
            else leves++;
        });
        return {leves,graves,mortales,total:allIncidents.length};
    }

    function computeComunaStats() {
        const mapC = new Map();
        allIncidents.forEach(i=>{
            const c=i.properties.comuna||'Desconocido';
            mapC.set(c,(mapC.get(c)||0)+1);
        });
        return Array.from(mapC.entries()).sort((a,b)=>b[1]-a[1]).slice(0,5).map(([n,c])=>({name:n,count:c}));
    }

    function updateBarStats() {
        const s = computeSeverityStats();
        const total = s.total;
        document.getElementById('severityBarsContainer').innerHTML = `
            <div class="bar-item"><div class="bar-label"><span><i class="fa-regular fa-circle-check"></i> Leves</span><span>${s.leves} (${total?((s.leves/total)*100).toFixed(0):0}%)</span></div><div class="bar-bg"><div class="bar-fill fill-leve" style="width:${total?(s.leves/total)*100:0}%;"></div></div></div>
            <div class="bar-item"><div class="bar-label"><span><i class="fa-solid fa-truck-medical"></i> Graves</span><span>${s.graves} (${total?((s.graves/total)*100).toFixed(0):0}%)</span></div><div class="bar-bg"><div class="bar-fill fill-grave" style="width:${total?(s.graves/total)*100:0}%;"></div></div></div>
            <div class="bar-item"><div class="bar-label"><span><i class="fa-solid fa-skull"></i> Mortales</span><span>${s.mortales} (${total?((s.mortales/total)*100).toFixed(0):0}%)</span></div><div class="bar-bg"><div class="bar-fill fill-mortal" style="width:${total?(s.mortales/total)*100:0}%;"></div></div></div>
            <div class="bar-hint">Total incidentes en Colombia: ${total}</div>`;
        const top = computeComunaStats();
        let html='';
        if (top.length) top.forEach((c,i)=>{ const percent = (c.count/top[0].count)*100; html+=`<div class="bar-item"><div class="bar-label"><span>${i+1} ${c.name}</span><span>${c.count}</span></div><div class="bar-bg"><div class="bar-fill fill-comuna" style="width:${percent}%;"></div></div></div>`; });
        else html='<div class="text-xs text-center py-2">Sin datos</div>';
        document.getElementById('comunaBarsContainer').innerHTML = html;
    }

    function loadInitialIncidents() {
        const geo = <?php echo json_encode($geoJsonData); ?>;
        if (geo?.features) {
            allIncidents = [...geo.features];
            totalIncidentsCount = allIncidents.length;
            let crit=0;
            allIncidents.forEach(inc=>{
                const g=(inc.properties.gravedad||'').toUpperCase();
                if (g.includes('MUERTO')||g.includes('FATAL')||g.includes('DECESO')) crit++;
                addIncidentToMap(inc);
            });
            criticalCount=crit;
            document.getElementById('totalIncidentsLabel').innerHTML = totalIncidentsCount+' registros';
            document.getElementById('criticalIncidentsLabel').innerHTML = criticalCount+' alertas';
            document.getElementById('footerTotalIncidents').innerHTML = totalIncidentsCount;
            document.getElementById('sCrit').innerHTML = criticalCount;
            updateBarStats();
            updateHeatmap();
        }
    }

    function zoomIn() { if(map) map.zoomIn(); }
    function zoomOut() { if(map) map.zoomOut(); }

    function locateUser() {
        if (!navigator.geolocation) { alert("Geolocalización no soportada."); return; }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat=pos.coords.latitude, lng=pos.coords.longitude, acc=pos.coords.accuracy;
                map.setView([lat,lng],15);
                if (userMarker) map.removeLayer(userMarker);
                if (userCircle) map.removeLayer(userCircle);
                const userIcon = L.divIcon({ html:'<div style="background:#3b82f6; width:16px; height:16px; border-radius:50%; border:3px solid white; box-shadow:0 0 8px rgba(0,0,0,0.5);"></div>', iconSize:[16,16] });
                userMarker = L.marker([lat,lng], { icon:userIcon }).addTo(map).bindPopup(`<strong>Tu ubicación</strong><br>Precisión: ±${Math.round(acc)} m`).openPopup();
                userCircle = L.circle([lat,lng], { radius:acc, color:'#3b82f6', fillColor:'#3b82f6', fillOpacity:0.15, weight:1.5 }).addTo(map);
            },
            (err) => { alert("No se pudo obtener ubicación: "+err.message); },
            { enableHighAccuracy:true, timeout:10000 }
        );
    }

    function refreshIncidentList() {
        const container = document.getElementById('incidentListContainer');
        if (!allIncidents.length) { container.innerHTML = '<p class="text-center py-6">No hay registros.</p>'; return; }
        container.innerHTML = allIncidents.slice().reverse().map(i=>{
            const p=i.properties;
            const gClass = p.gravedad.toLowerCase().includes('mortal')?'gravedad-mortal':(p.gravedad.toLowerCase().includes('grave')?'gravedad-grave':'gravedad-leve');
            return `<div class="incident-card"><div class="flex justify-between"><span class="font-bold text-sm">${p.clase}</span><span class="gravedad-badge ${gClass}">${p.gravedad}</span></div><div class="text-xs text-slate-400 mt-1"><i class="fa-solid fa-location-dot"></i> ${p.direccion} (${p.barrio})<br><i class="fa-solid fa-clock"></i> ${p.fecha} ${p.hora}<br><i class="fa-solid fa-flag"></i> ${p.comuna}</div><button class="btn-view-map mt-2" onclick="flyToIncident(${i.geometry.coordinates[1]}, ${i.geometry.coordinates[0]})">Enfocar mapa</button></div>`;
        }).join('');
    }

    function flyToIncident(lat,lng) {
        document.getElementById('incidentModal').classList.remove('active');
        isModalOpen=false;
        map.flyTo([lat,lng],17,{duration:1.5});
    }

    // -------------------- UI --------------------
    function setupUI() {
        document.getElementById('zoomInBtn').onclick = zoomIn;
        document.getElementById('zoomOutBtn').onclick = zoomOut;
        document.getElementById('btnGeolocate').onclick = locateUser;
        document.getElementById('btnSafeRoute').onclick = () => {
            if (selectingDestination) { deactivateRouteSelection(); document.getElementById('routeUpdate').innerHTML = 'Selección cancelada'; }
            else activateRouteSelection();
        };
        document.getElementById('toggleHeatmapBtn').onclick = toggleHeatmap;
        document.getElementById('alertAllBtn').onclick = () => { mainClusterGroup.clearLayers(); allIncidents.forEach(i=>addIncidentToMap(i)); updateHeatmap(); };
        document.getElementById('alertDeathsBtn').onclick = () => {
            mainClusterGroup.clearLayers();
            allIncidents.forEach(i=>{
                const g=(i.properties.gravedad||'').toUpperCase();
                if (g.includes('MUERTO')||g.includes('FATAL')||g.includes('DECESO')) addIncidentToMap(i);
            });
        };
        const sb = document.getElementById('sidebar');
        document.getElementById('btnMenu').onclick = () => sb.classList.toggle('hidden');
        document.getElementById('closeSidebar').onclick = () => sb.classList.add('hidden');
        const modal = document.getElementById('incidentModal');
        document.getElementById('btnListIncidents').onclick = () => { modal.classList.add('active'); isModalOpen=true; refreshIncidentList(); };
        document.getElementById('closeModalBtn').onclick = () => { modal.classList.remove('active'); isModalOpen=false; };
    }

    window.onload = () => {
        initMap();
        loadInitialIncidents();
        fetchWeather();
        fetchAirQuality();
        fetchSunriseSunset();
        setInterval(fetchWeather, 900000);
        setInterval(fetchAirQuality, 1800000);
        setInterval(fetchSunriseSunset, 3600000);
        setupUI();
    };
</script>

<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/transicontrol/sw.js')
            .then(reg => console.log('Service Worker registrado', reg))
            .catch(err => console.error('Error al registrar SW', err));
    }
</script>
</body>
</html>
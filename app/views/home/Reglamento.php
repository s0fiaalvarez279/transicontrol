<?php
// Datos reales extraídos de transito.js
$traffic_codes = [
    ["number" => 1, "title" => "ÁMBITO DE APLICACIÓN Y PRINCIPIOS", "content" => "Las normas del presente Código rigen en todo el territorio nacional y regulan la circulación de peatones, usuarios, pasajeros, conductores, motociclistas, ciclistas, agentes de tránsito, y vehículos por las vías públicas o privadas..."],
    ["number" => 2, "title" => "DEFINICIONES", "content" => "Acera o andén: Franja longitudinal de la vía urbana, destinada exclusivamente a la circulación de peatones. Accidente de tránsito: Evento generalmente involuntario que causa daños a personas y bienes."],
    ["number" => 3, "title" => "AUTORIDADES DE TRÁNSITO", "content" => "Son autoridades de tránsito el Ministerio de Transporte, los Gobernadores y Alcaldes, los organismos de tránsito departamental/municipal, la Policía Nacional, los Inspectores de Tránsito, y los agentes de Tránsito."],
    ["number" => 26, "title" => "CAUSALES DE SUSPENSIÓN O CANCELACIÓN", "content" => "La licencia se suspende por imposibilidad transitoria física o mental, decisión judicial, embriaguez, reincidir en la misma infracción, o prestar servicio público con vehículos particulares."],
    ["number" => 42, "title" => "SEGUROS OBLIGATORIOS", "content" => "Para poder transitar en territorio nacional todos los vehículos deben estar amparados por seguro obligatorio vigente (SOAT)."],
    ["number" => 135, "title" => "PROCEDIMIENTO", "content" => "Ante contravención, autoridad ordena detener vehículo y extiende orden de comparendo en 3 días hábiles."]
    // ... Aquí puedes iterar los 170 artículos mapeados en tu DB o arreglo extendido.
];

// Lógica de filtrado en el servidor
$search = isset($_GET['search_article']) ? trim($_GET['search_article']) : '';
$expanded_art = isset($_GET['expand']) ? (int)$_GET['expand'] : null;

$filtered_codes = array_filter($traffic_codes, function($item) use ($search) {
    if (empty($search)) return true;
    return (strpos(strtolower($item['title']), strtolower($search)) !== false || 
            strpos(strtolower($item['content']), strtolower($search)) !== false ||
            $item['number'] == $search);
});
?>

<div class="search-container" style="margin-bottom: 24px;">
    <form method="GET" action="" style="display: flex; gap: 10px;">
        <input type="text" name="search_article" class="search-input" 
               placeholder="Buscar por artículo, título o palabra clave (ej. SOAT)..." 
               value="<?php echo htmlspecialchars($search); ?>"
               style="flex: 1; padding: 12px; background: #1e293b; border: 1px solid #334155; color: #f1f5f9; border-radius: 8px;">
        <button type="submit" class="btn-search" style="padding: 12px 24px; background: #2563eb; color: #fff; border: none; border-radius: 8px; cursor: pointer;">
            <i class="fas fa-search"></i> Filtrar
        </button>
        <?php if (!empty($search)): ?>
            <a href="?" style="padding: 12px; background: #475569; color: #fff; border-radius: 8px; text-decoration: none;">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<div class="articles-grid" style="display: grid; grid-template-columns: 1fr; gap: 16px;">
    <?php if (empty($filtered_codes)): ?>
        <p style="color: #94a3b8; text-align: center;">No se encontraron artículos que coincidan con la búsqueda.</p>
    <?php else: ?>
        <?php foreach ($filtered_codes as $code): 
            $isOpen = ($expanded_art === $code['number']);
        ?>
            <div class="article-card" style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 16px; transition: all 0.3s ease;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 1rem; color: #f1f5f9; margin: 0; font-weight: 600;">
                        <span style="color: #3b82f6; margin-right: 8px;">Art. <?php echo $code['number']; ?></span>
                        <?php echo htmlspecialchars($code['title']); ?>
                    </h3>
                    <a href="?search_article=<?php echo urlencode($search); ?>&expand=<?php echo $isOpen ? '' : $code['number']; ?>" 
                       style="color: #3b82f6; text-decoration: none; font-size: 13px; font-weight: 500;">
                        <?php echo $isOpen ? 'Ocultar detalles ▲' : 'Ver contenido ▼'; ?>
                    </a>
                </div>
                
                <?php if ($isOpen): ?>
                    <div class="article-content" style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #334155; color: #cbd5e1; font-size: 14px; line-height: 1.6;">
                        <?php echo htmlspecialchars($code['content']); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
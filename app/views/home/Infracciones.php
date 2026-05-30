<?php
// Arreglo de infracciones simulado (puedes reemplazarlo por una consulta a tu Base de Datos)
$infracciones = [
    ["codigo" => "C02", "descripcion" => "Estacionar un vehículo en sitios prohibidos.", "multa" => "15 SMLDV"],
    ["codigo" => "D02", "descripcion" => "Conducir un vehículo sin portar los seguros obligatorios de ley.", "multa" => "30 SMLDV"],
    ["codigo" => "C35", "descripcion" => "No realizar la revisión técnico-mecánica en el plazo legal.", "multa" => "15 SMLDV"]
];
?>

<div class="infraction-grid">
    <?php foreach ($infracciones as $inf): ?>
        <div class="infraction-card">
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 12px; margin-bottom: 12px;">
                <span class="article-num"><?php echo htmlspecialchars($inf['codigo']); ?></span>
                <span class="infrac-penalty"><?php echo htmlspecialchars($inf['multa']); ?></span>
            </div>
            <h3 class="infrac-title" style="color: #1E293B; margin-bottom: 8px;">Infracción Frecuente</h3>
            <p style="font-size: 13px; color: #5A6C7E; line-height: 1.4;"><?php echo htmlspecialchars($inf['descripcion']); ?></p>
        </div>
    <?php endforeach; ?>
</div>
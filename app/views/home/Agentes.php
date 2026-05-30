<?php
$agentes = [
    ["id" => 1, "name" => "Oficial Juan Pérez", "badge" => "AGT-4402", "rating" => 4, "jurisdiction" => "Zona Norte / Centro"],
    ["id" => 2, "name" => "Oficial María Rodríguez", "badge" => "AGT-8819", "rating" => 5, "jurisdiction" => "Zona Sur / Metropolitana"]
];

// Simulamos el estado de selección (por ejemplo, si viene de un parámetro $_GET['selected_agent'])
$selectedAgentId = isset($_GET['selected_agent']) ? (int)$_GET['selected_agent'] : null;
?>

<div class="profile-grid">
    <?php foreach ($agentes as $agent): 
        $isSelected = ($selectedAgentId === $agent['id']);
        $cardStyle = $isSelected ? 'border-color: #0F4C81; background: #F8FAFE;' : '';
    ?>
        <div class="profile-card <?php echo $isSelected ? 'open' : ''; ?>" style="cursor: pointer; <?php echo $cardStyle; ?>">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; background: rgba(15,76,129,0.1); color: #0F4C81; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h3 style="font-size: 1rem; font-weight: 700; color: #1E293B; margin: 0;"><?php echo htmlspecialchars($agent['name']); ?></h3>
                    <span style="font-size: 11px; color: #5A6C7E; font-weight: 500;">Placa: <?php echo htmlspecialchars($agent['badge']); ?></span>
                </div>
            </div>
            
            <div class="rating-stars">
                <?php for ($star = 1; $star <= 5; $star++): ?>
                    <span class="star-btn <?php echo $star <= $agent['rating'] ? 'active' : ''; ?>" style="font-size: 14px; padding: 2px 4px;">★</span>
                <?php endfor; ?>
            </div>

            <div class="contact-row" style="margin-top: 8px;">
                <i class="fas fa-map-marker-alt" style="color: #8BA0BC;"></i>
                <span>Jurisdicción: <?php echo htmlspecialchars($agent['jurisdiction']); ?></span>
            </div>

            <button class="btn-details" style="width: 100%; text-align: center; margin-top: 12px;">
                <?php echo $isSelected ? 'Ocultar historial' : 'Ver calificaciones y reportes'; ?>
            </button>
        </div>
    <?php endforeach; ?>
</div>
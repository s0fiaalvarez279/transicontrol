<?php
$veedores = [
    ["id" => 1, "name" => "Alejandro Tobón", "registration" => "VEE-2026-09A"],
    ["id" => 2, "name" => "Diana Marcela Ruiz", "registration" => "VEE-2026-14B"]
];
?>

<div class="profile-grid">
    <?php foreach ($veedores as $obs): ?>
        <div class="profile-card" style="border-left: 4px solid #1E88E5;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #1E293B; margin: 0;"><?php echo htmlspecialchars($obs['name']); ?></h3>
                <span style="font-size: 11px; background: #DEF7EC; color: #03543F; padding: 2px 8px; border-radius: 10px; font-weight: 600;">Activo</span>
            </div>
            
            <p style="font-size: 12px; color: #5A6C7E; margin-bottom: 8px;">Veeduría de Movilidad y Control Ciudadano</p>
            
            <div class="contact-row">
                <i class="fas fa-id-badge" style="color: #8BA0BC; width: 16px;"></i>
                <span>Reg: <?php echo htmlspecialchars($obs['registration']); ?></span>
            </div>

            <button class="btn-details" style="width: 100%; background: #0F4C81; color: white; border-radius: 12px; padding: 8px 0; font-weight: 600;">
                <i class="fas fa-bullhorn" style="margin-right: 4px;"></i> Solicitar Acompañamiento
            </button>
        </div>
    <?php endforeach; ?>
</div>
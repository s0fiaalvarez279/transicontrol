<?php
// Mapeo exacto de la constante HEARINGS de transito.js
$audiencias = [
    ["date" => "2026-06-02", "time" => "08:00", "case" => "Infracción velocidad excesiva", "judge" => "Dr. Hernández", "location" => "Juzgado de Tránsito Centro", "description" => "Caso de conductor en zona escolar con fotomulta."],
    ["date" => "2026-06-02", "time" => "09:30", "case" => "Licencia vencida / Reincidencia", "judge" => "Dra. Martínez", "location" => "Juzgado de Tránsito Centro", "description" => "Renovación de licencia denegada por inconsistencia RUNT."],
    ["date" => "2026-06-03", "time" => "11:00", "case" => "Accidente sin lesiones graves", "judge" => "Dr. González", "location" => "Juzgado de Tránsito Sur", "description" => "Impugnación de informe descriptivo de choque por cruce indebido."],
    ["date" => "2026-06-05", "time" => "08:00", "case" => "Conducir bajo influjo de embriaguez", "judge" => "Dra. López", "location" => "Juzgado de Tránsito Centro", "description" => "Alcoholimetría grado 2. Audiencia de apelación de suspensión legal."]
];
?>

<div class="hearings-container" style="background: #111827; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #f1f5f9; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-gavel" style="color: #ef4444;"></i> Agenda de Audiencias de Impugnación
        </h2>
        <span style="font-size: 12px; background: rgba(239, 68, 68, 0.1); color: #f87171; padding: 4px 12px; border-radius: 20px; font-weight: 600;">
            Sincronizado con Organismos de Tránsito
        </span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 14px;">
        <?php foreach ($audiencias as $hearing): ?>
            <div class="hearing-item" style="background: #1e293b; border-left: 4px solid #3b82f6; border-radius: 8px; padding: 16px; display: flex; flex-direction: column; gap: 8px; transition: transform 0.2s;">
                <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <span style="font-size: 11px; background: #2563eb; color: #fff; padding: 2px 8px; border-radius: 4px; font-weight: 600; text-transform: uppercase; margin-right: 6px;">
                            <?php echo htmlspecialchars($hearing['date']); ?> - <?php echo htmlspecialchars($hearing['time']); ?>
                        </span>
                        <strong style="font-size: 14px; color: #f1f5f9;"><?php echo htmlspecialchars($hearing['case']); ?></strong>
                    </div>
                    <span style="font-size: 13px; color: #94a3b8; font-style: italic;">
                        Asignado a: <?php echo htmlspecialchars($hearing['judge']); ?>
                    </span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 16px; font-size: 13px; color: #cbd5e1; margin-top: 4px;">
                    <div>
                        <i class="fas fa-map-marker-alt" style="color: #64748b; margin-right: 4px;"></i>
                        <strong>Sede:</strong> <?php echo htmlspecialchars($hearing['location']); ?>
                    </div>
                </div>

                <p style="font-size: 13px; color: #94a3b8; line-height: 1.4; margin: 4px 0 0 0; background: rgba(15, 23, 42, 0.3); padding: 8px; border-radius: 6px;">
                    <strong>Detalle del caso:</strong> <?php echo htmlspecialchars($hearing['description']); ?>
                </p>
                
                <div style="display: flex; justify-content: flex-end; margin-top: 6px;">
                    <a href="#" class="btn-action" style="font-size: 12px; color: #3b82f6; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                        <i class="fas fa-file-signature"></i> Solicitar Apoderado o Veedor para esta Sala
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
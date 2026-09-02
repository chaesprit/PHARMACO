<?php

/**
 * Marque PHARMACO : le pictogramme (ordonnance + croix de pharmacie +
 * gélule + « pixels » numériques) redessiné en SVG, suivi du mot-symbole.
 * Les couleurs viennent de variables CSS (--logo-*) pour que la marque
 * s'adapte au fond : pleine couleur sur fond clair, claircie sur la
 * barre de navigation bleue. Inclus via VUES_DIR . 'partials/logo.php'.
 */
?>
<span class="marque">
    <svg class="marque-picto" viewBox="0 0 48 48" role="img" aria-label="PHARMACO">
        <!-- ordonnance -->
        <path class="picto-doc" d="M21 4h14l7 7v24a3 3 0 0 1-3 3H21a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3z"/>
        <path class="picto-pli" d="M35 4l7 7h-7z"/>
        <rect class="picto-ligne" x="22.5" y="11" width="12" height="2.2" rx="1.1"/>
        <rect class="picto-ligne" x="22.5" y="15.4" width="12" height="2.2" rx="1.1"/>
        <!-- croix de pharmacie -->
        <rect class="picto-croix" x="10.5" y="14.5" width="13.5" height="27.5" rx="3.6"/>
        <rect class="picto-croix" x="3.5" y="22.5" width="27.5" height="13.5" rx="3.6"/>
        <!-- gélule -->
        <g transform="rotate(-20 17.25 29.25)">
            <rect class="picto-gelule" x="5.5" y="24.5" width="23.5" height="9.5" rx="4.75"/>
            <path class="picto-gelule-clair" d="M10.25 24.5a4.75 4.75 0 0 0 0 9.5H17.25v-9.5z"/>
            <rect class="picto-gelule-trait" x="16.75" y="24.5" width="1.1" height="9.5"/>
        </g>
        <!-- pixels -->
        <rect class="picto-pixel" x="39.5" y="9.5" width="4.6" height="4.6" rx="1"/>
        <rect class="picto-pixel" x="43.3" y="3.7" width="4.2" height="4.2" rx="1"/>
        <rect class="picto-pixel picto-pixel-faible" x="42.8" y="15.2" width="3" height="3" rx=".7"/>
    </svg>
    <span class="marque-mot">PHARMACO</span>
</span>

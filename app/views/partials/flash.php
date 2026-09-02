<?php

/**
 * Affiche le message flash éventuel (défini par Controller::flash())
 * puis le retire de la session pour qu'il ne réapparaisse pas.
 * Inclus juste après la navigation, donc présent sur toutes les pages.
 */
if (!empty($_SESSION['flash'])):
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $type = $flash['type'] === 'erreur' ? 'erreur' : 'succes';
?>
<div class="bandeau-flash bandeau-flash-<?= $type ?>" role="status">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

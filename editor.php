<?php
/**
 * Module Page to manage Dashboard
 * 
 */

namespace STPH\recordHomeDashboard;

renderPageTitle('<i class="fas fa-columns"></i> Dashboard Editor');

/** @var \STPH\recordHomeDashboard\recordHomeDashboard $module */
if( $module->isDisabledEditor() ) : ?>

    <!-- Dashboard Editor Disabled Message -->
    <div style="width:950px;max-width:960px;" class="d-none d-md-block mt-3 mb-3"><?= $module->tt("module_page_disabled") ?></div>

<?php else: ?>

    <div style="width:950px;max-width:960px;" class="d-none d-md-block mt-3 mb-3"><?= $module->tt("module_page_subtitle") ?></div>
    <!-- Dashboard Editor Render Target -->
    <div id="STPH_DASHBOARD_EDITOR"></div>
    <!-- Scripts and Styles -->
    <script>

        const stph_rhd_getBaseUrlFromBackend = function() {
            return '<?= $module->getBaseUrl() ?>'
        }

        const stph_rhd_getRepeatingInstruments = function() {
            return <?= json_encode($module->getRepeatingForms()) ?>
        }

    </script>
    <script src="<?= $module->getUrl("./dist/appEditor.js") ?>"></script>
    <link rel="stylesheet" href="<?= $module->getUrl('dist/style.css')?>">

<?php endif; ?>

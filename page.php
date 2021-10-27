<?php

namespace STPH\recordHomeDashboard;

renderPageTitle('<i class="fas fa-columns"></i> Record Home Dashboard');
print '<div style="width:950px;max-width:950px;" class="d-none d-md-block mt-3 mb-3">'.$module->tt("module_page_subtitle").'</div>';


?>


<div id="appEditor"></div>
<script>
    const getPage = function () {
        return '<?= PAGE ?>';
    }
</script>
<script src="<?= $module->getUrl("./js/appEditor.js") ?>"></script>
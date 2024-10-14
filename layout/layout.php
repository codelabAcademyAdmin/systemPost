<div class="layout">
    <div class="layout-aside">
        <div class="layout-aside-content">
            <?php require('./components/Menu/Menu.php'); ?>
        </div>
    </div>
    <div class="layout-section">
        <div class="layout-section-header">
            <?php require('./components/Header/Header.php'); ?>
        </div>
        <div class="layout-section-content">
            <?php $AppViews->loadViews(); ?>
        </div>
    </div>
</div>
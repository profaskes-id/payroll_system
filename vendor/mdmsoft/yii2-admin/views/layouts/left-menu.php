<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$controller = $this->context;
$menus = $controller->module->menus;
$route = $controller->route;
foreach ($menus as $i => $menu) {
    $menus[$i]['active'] = strpos($route, trim($menu['url'][0], '/')) === 0;
}
$this->params['nav-items'] = $menus;
?>
<?php $this->beginContent($controller->module->mainLayout) ?>
<div class="row">
<?php
    $assignments = Yii::$app->authManager->getAssignments(Yii::$app->user->id);
    ?>

    <?php if (!empty($assignments)) : ?>
        <?php 
        $roleNames = array_column($assignments, 'roleName');
        $isSuperAdmin = in_array('super_admin', $roleNames, true);
        ?>
        
        <?php if ($isSuperAdmin) : ?>
            <div class="col-sm-3">
        <div id="manager-menu" class="list-group">
            <?php
            foreach ($menus as $menu) {
                $label = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']) .
                    Html::tag('span', Html::encode($menu['label']), []);
                $active = $menu['active'] ? ' active' : '';
                echo Html::a($label, $menu['url'], [
                    'class' => 'list-group-item' . $active,
                ]);
            }
         ?>
        </div>
    </div> 
    <div class="col-sm-9">
        <?= $content ?>
    </div>
</div>
        <?php else : ?>
    <div class="col-12">
        <?= $content ?>
    </div>
</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    list(, $url) = Yii::$app->assetManager->publish('@mdm/admin/assets');
    $this->registerCssFile($url . '/list-item.css');
    ?>

    <?php $this->endContent(); ?>
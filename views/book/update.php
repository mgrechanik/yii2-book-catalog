<?php

use app\models\forms\BookUpdateForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var BookUpdateForm $model */

$this->title = 'Update Book: ' . $arModel->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $arModel->name, 'url' => ['view', 'id' => $arModel->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="book-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form_update', [
        'model' => $model,
    ]) ?>

</div>

<?php

use app\models\forms\BookCreateForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var BookCreateForm$model */

$this->title = 'Создание книги';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form_create', [
        'model' => $model,
    ]) ?>

</div>

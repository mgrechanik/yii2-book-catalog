<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\entities\Book $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        if (!Yii::$app->user->isGuest) {
    ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
        </p>
    <?php
        }
    ?>

    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        'description:ntext',
        'isbn',
        'year',
        'photo' => [
            'attribute' => 'photo',
            'format' => 'html',
            'label' => 'Обложка',
            'value' => function ($model) {
                return Html::img($model->photo, ['height' => 150, 'width' => 'auto']);
            },
        ],
        'authors' => [
            'attribute' => 'authors',
            'format' => 'raw',
            'label' => 'Авторы',
            'value' => function ($model) {
                $authors = $model->authors;
                $items = [];
                foreach ($authors as $author) {
                    $items[] = Html::a(Html::encode($author->fio), ['/author/view', 'id' => $author->id], ['target' => '_blank']);
                }
                return Html::ul($items, ['encode' => false]);

            },
            ]
    ],
]) ?>

</div>

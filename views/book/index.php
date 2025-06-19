<?php

use app\models\entities\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\search\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user;

?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (!$user->isGuest) { ?>
        <p>
            <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php } ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description:ntext',
            'year',
            'isbn',
            'photo',
            [
                'attribute' => 'author',
                'format' => 'html',
                'label' => 'Обложка',
                'value' => function ($model) {
                    $authors = $model->authors;
                    $items = [];
                    foreach ($authors as $author) {
                        $items[] = $author->fio;
                    }
                    return Html::ul($items);
                },
            ],
            //'id_user',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Book $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>

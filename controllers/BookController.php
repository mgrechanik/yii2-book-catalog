<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\entities\Book;
use app\models\search\BookSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\forms\BookCreateForm;
use app\models\forms\BookUpdateForm;
use yii\web\UploadedFile;
use app\useCases\book\BookManageService;
use Yii;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    private BookManageService $service;
    public function __construct($id, $module, BookManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
        /*
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );*/
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        //$form = new BookCreateForm();
        $form = Yii::createObject([
            'class' => BookCreateForm::class
        ]);
        //var_dump($form);
        //var_dump($form->author7);
        //var_dump($form->attributes());
        //die();
        //var_dump($this->service);die();
        if ($this->request->isPost) {
            if ($form->load($this->request->post()) && $form->validate()) {
                if ($form->imageFile = UploadedFile::getInstance($form, 'imageFile')) {
                    $form->upload();
                }

                try {
                    $book = $this->service->create($form);
                    Yii::$app->session->setFlash('success', 'Книга была успешно создана');
                    return $this->redirect(['view', 'id' => $book->id]);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!$model->checkCreatedByUser(Yii::$app->user->id)) {
            throw new \yii\web\ForbiddenHttpException('Доступ на управление запрещён к книгам, созданным не Вами.');
        }

        //$form = new BookUpdateForm($model);
        $form = Yii::createObject([
            'class' => BookUpdateForm::class
        ], [$model]);

        if ($this->request->isPost && $form->load($this->request->post()) && $form->validate()) {
            if ($form->imageFile = UploadedFile::getInstance($form, 'imageFile')) {
                $form->upload();
            }

            try {
                $this->service->update($form);
                Yii::$app->session->setFlash('success', 'Книга была успешно изменена');
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'arModel' => $model
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!$model->checkCreatedByUser(Yii::$app->user->id)) {
            throw new \yii\web\ForbiddenHttpException('Доступ на управление запрещён к книгам, созданным не Вами.');
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

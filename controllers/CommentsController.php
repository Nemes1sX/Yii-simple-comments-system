<?php

namespace app\controllers;

use Yii;
use app\models\Comments;
use app\models\CommentsSearch;
use yii\data\Pagination; 
use yii\data\ActiveDataProvide;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * CommentsController implements the CRUD actions for Comments model.
 */
class CommentsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['userindex','index','view','create','update','delete','find'],
                'rules' => [
                    [
                      'allow' => true,
                      'actions' => ['userindex'],
                      'roles' => ['?'],   
                    ],
                    [
                        'allow' => true,
                        'actions' => ['userindex','index','view','create', 'update', 'delete', 'find'],
                        'roles' => ['@'],
                    ],
                 ],
            ],
        ];
    }

    /**
     * Lists all Comments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=5;
        return $this->render('index', [   
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUserindex(){
       $query = Comments::find();
       $pagination = new Pagination(['defaultPageSize' => 5 , 'totalCount' => count($query->all())]);
       $models = $query->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        return $this->render('userindex', [
            'models' => $models,
            'pagination' => $pagination
        ]);
    }
    /**
     * Displays a single Comments model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Comments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Comments();

        $model->logged_user = Yii::$app->user->identity->username;  
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Comments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
  
        $model = $this->findModel($id);

        $session = Yii::$app->session;   


        if(Yii::$app->user->identity->username == (new \yii\db\Query())->select('logged_user')->from('comments')->where('id' == $id)){

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }else{
         $session->setFlash('error', 'You cannot edit other person comments'); 
        return $this->redirect(['index']);
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Comments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $session = Yii::$app->session;   


        if(Yii::$app->user->identity->username == (new \yii\db\Query())->select('logged_user')->from('comments')->where('id' == $id)){ 
        
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
        }
        else{
            $session->setFlash('error', 'You cannot delete other person comments');
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Comments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

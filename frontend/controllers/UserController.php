<?php

namespace frontend\controllers;

use common\models\User;
use common\models\UserSearch;
use frontend\components\Image;
use frontend\models\Company;
use frontend\models\UserImage;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view','delete','create','update'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();
        $model->skipActivate = 1;
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                if(empty($model->company_id)){
                    $company = new Company();
                    $company->load($this->request->post());
                    if($company->save()){
                        $model->company_id = $company->id;
                    }
                }
               
                if($model->skipActivate){
                    $model->status = User::STATUS_ACTIVE;
                    $result = $model->sendPassword();
                    $model->save();
                }else{
                    $result = $model->sendEmail();
                }
                if($result){
                    Yii::$app->session->setFlash('success','De gebruiker is succesvol aangemaakt');
                }
                
                return $this->redirect(['index']);
            }
            Yii::$app->session->setFlash('warning','Er is wat misgegaan met aanmaken van de gebruiker');
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCheckImages(){
        $userimages = UserImage::find()->where(['!=','image',''])->andWhere(['checked'=>false])->all();

        foreach ($userimages as $userimage) {
            $new_image = Image::resizeImage(base64_decode($userimage->image));
            if(!empty($new_image)){
                $userimage->checked = true;
                $userimage->user->imageApp = base64_encode($new_image);
                if($userimage->user->save()){
                    
                }   
            } 
        }

        return $this->redirect('index');
    }
    public function actionFetchdata()
    {
        $users = User::find()->where('url != ""')->all();
        foreach ($users as $user) {
            $user->fetchData();
            // $url = $user->url;
            // $page = file_get_contents($url);
            // $start_pos = strpos($page, '<div class="margin-top-50">');
            // $text1 = substr($page, $start_pos);
            // $end_pos = strpos($text1, '</div>');
            // $final_text = substr($text1, 27, $end_pos - 27);
            // $user->description = $final_text;
            // $user->save();
        }
        Yii::$app->session->setFlash('success','Data is opgehaald');
        return $this->redirect(['/user/index']);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

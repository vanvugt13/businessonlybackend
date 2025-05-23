<?php

namespace frontend\controllers;

use frontend\components\Image;
use frontend\models\Company;
use frontend\models\CompanyImage;
use frontend\models\CompanySearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
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
                            'actions' => ['index', 'view','delete','create','update','fetchdata','check-images'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Company models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
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

    public function actionCheckImages(){
        $companyImages = CompanyImage::find()->where('image != ""')->andWhere(['checked'=>0])->all();
        foreach($companyImages as $companyImage){
           
            $new_image = Image::resizeImage(base64_decode($companyImage->image));
            if(!empty($new_image)){
                $companyImage->image = base64_encode($new_image);
                $companyImage->checked = 1;
                if(!$companyImage->save()){
                   echo "mislukt";
                   exit;
                    
                }                
            }
        }
        return $this->redirect('index');
    }
    public function actionFetchdata()
    {
        $companies = Company::find()->where('url != ""')->all();
        foreach ($companies as $company) {
            $company->fetchData();
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
        return $this->redirect(['/company/index']);
    }


    // public function actionFetchDataOne(string $type,int $id=null){
    //     $model = new Company();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post()) && $model->save()) {
    //             $model->fetchData();
    //             return $this->redirect([$type,'id'=>$id]);
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render($type, [
    //         'model' => $model,
    //     ]);
    // }
    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Company();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
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
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

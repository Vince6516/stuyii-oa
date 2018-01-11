<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','error','dev-data','intro-data'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update'],
                        // 自定义一个规则，返回true表示满足该规则，可以访问，false表示不满足规则，也就不可以访问actions里面的操作啦
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->id == 1 ? true : false;
                        },
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public  function actionDevData(){
        $sql_AMT = "P_oa_New_Product_Performance";
        $DataAMT = Yii::$app->db->createCommand($sql_AMT) ->queryAll();
        $data['salername'] = array_column($DataAMT, 'salername');
        $data['OneMonth'] = array_column($DataAMT, 'OneMonth');
        $data['ThreeMonth'] = array_column($DataAMT, 'ThreeMonth');
        $data['SixMonth'] = array_column($DataAMT, 'SixMonth');
        $result['salername'] = $data;
        $result['introducer'] = $this->actionIntroData();
        echo json_encode($result);
    }
    /**
     * Introducer data
     * @return array
     */
    public  function actionIntroData(){
        $sql_AMT = "P_oa_Intro_Product_Performance";
        $DataAMT = Yii::$app->db->createCommand($sql_AMT)->queryAll();
        $data['introducer'] = array_column($DataAMT, 'introducer');
        $data['OneMonth'] = array_column($DataAMT, 'OneMonth');
        $data['ThreeMonth'] = array_column($DataAMT, 'ThreeMonth');
        $data['SixMonth'] = array_column($DataAMT, 'SixMonth');
        return $data;
    }

    /*
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}

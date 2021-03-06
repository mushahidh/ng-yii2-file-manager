<?php
namespace api\modules\v1\controllers;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior ;
use yii\db\Expression;
use common\components\AccessRule;
use yii\data\ActiveDataProvider;
use common\models\User;
use common\models;
use common\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use Yii;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UploadController extends ActiveController
{

    public $documentPath;
    public $modelClass = '';
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
          [
            'class' => \yii\filters\Cors::className(),
        ],
           [
           'class' => CompositeAuth::className(),
               'except' => ['options'],
           'authMethods' => [
           HttpBearerAuth::className(),

           
           ],
        ],
       
           [
           'class' => TimestampBehavior::className(),

           ],
             [
              'class' => 'yii\filters\ContentNegotiator',
              'only' => ['upload'],  // in a controller
              // if in a module, use the following IDs for user actions
              // 'only' => ['user/view', 'user/index']
              'formats' => [
                  'application/json' => Response::FORMAT_JSON,
              ],
           
          ],
           [
           'class' => AccessControl::className(),
    // We will override the default rule config with the new AccessRule class
           'ruleConfig' => [
           'class' => AccessRule::className(),
           ],
           'only' => ['upload'],
           'rules' => [[
           'actions' => ['upload'],
           'allow' => true,
            // Allow users, moderators and admins to create
           'roles' => [
           User::ROLE_ADMIN,
           User::ROLE_USER,
           ],
        ],
    
      
         ],
         ],


           ]



           );

    }   
      public function actionUpload()
    {
        $model = new UploadForm();
        //return Yii::$app->getRequest()->getBodyParams();
       // return    $model->load(Yii::$app->getRequest()->getBodyParams(), '');
   
        if (Yii::$app->request->isPost) {

        	if (UploadedFile::getInstancesByName('data'))
        	{

            	$model->image= UploadedFile::getInstancesByName('data');
              
              if(isset($_POST['foldername']))
              {
            	$path=\Yii::getAlias('@common').'/upload/'.$_POST['foldername'].'/';
               }
            else
            {
                $path=\Yii::getAlias('@common').'/upload/';
            }
              FileHelper::createDirectory($path,0777,true);
   			  }
          $i=0;

           if ($model->validate()) 
           {
            $basename=array();
              foreach ($model->image as $file) {
                $basename[] = $file->name;

            	$file->saveAs($path.'/'. $basename[$i]);
                

            	$i++;
         	}
          $prefixed_array = preg_filter('/^/', $path, $basename); 
         // return sizeof( $prefixed_array);
         $model=new \common\models\Software;

          foreach($prefixed_array as $array)
          {
             

          $model->load(Yii::$app->getRequest()->getBodyParams(), '');
           $model->id = NULL; 
          $model->isNewRecord = true;
              
          $model->downloadlink=$array;
          if(!$model->save())
          {
             return $model->firstErrors;
          }
          else{

              
              continue;
          
          }
        }
        return "Software added successfully";
          
        }
         	else
            {
            	return $model->firstErrors;
            }
              
        }
    }


}
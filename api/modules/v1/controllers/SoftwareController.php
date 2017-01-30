<?php
namespace api\modules\v1\controllers;
use yii\helpers\FileHelper;
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
use yii\filters\auth\QueryParamAuth;

use Yii;
/* 

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class SoftwareController extends ActiveController
{
    public $modelClass = 'common\models\Software';  
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
            QueryParamAuth::className(),
           
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
           'only' => ['create-directory','list-directory'],
           'rules' => [[
           'actions' => ['create-directory','list-directory'],
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

    public function actionCreateDirectory()
    {
    	if(isset($_POST['foldername']))
      {
      $foldername=$_POST['foldername'];

    	$path=\Yii::getAlias('@common').'/upload/' ;
    	$folderpath=FileHelper::createDirectory($path.$foldername);
      if(!$folderpath)
      {
        Yii::$app->response->statusCode = 400;
          return Yii::t('app','Unable to create folder');
      }
      else
      {
         Yii::$app->response->statusCode =200;
          return Yii::t('app','Folder created successfully');
      }

    }
    else
    {
      
      $path=\Yii::getAlias('@common').'/upload/' ;
      FileHelper::createDirectory($path);
    }

    }
   public function expandDirectories($base_url) {
    	
      $directories = array();
      foreach(scandir($base_url) as $file) {
            if($file == '.' || $file == '..') continue;
            $dir = $base_url.DIRECTORY_SEPARATOR.$file;
            if(is_dir($dir)) {
                $directories []= $dir;
                $directories = array_merge($directories, $this->expandDirectories($dir));
            }
      }
      return $directories;
}
    public function actionListDirectory(){
    	$base_url=\Yii::getAlias('@common').'/'.'upload'.'\\';
      if(isset($_GET['foldername']))
      {
         $foldername=$_GET['foldername'];
    	   $directories = $this->expandDirectories($base_url.$foldername);

         $files=\yii\helpers\FileHelper::findFiles($base_url. $foldername,['recursive'=>FALSE]);
          $current_directory = glob($base_url.$foldername.'/*' , GLOB_ONLYDIR);
		  }
      else{
           $base_url=\Yii::getAlias('@common').'\\'.'upload';
           $directories = $this->expandDirectories($base_url);
          
           $files=\yii\helpers\FileHelper::findFiles($base_url,['recursive'=>FALSE]);
            $current_directory = glob($base_url.'/*' , GLOB_ONLYDIR);

      }
     
    $directory=[
    'directory'=> $directories,
    'files'=>$files,
    'current_directory'=>$current_directory,
    ];
return $directory;
}
public function actionListFolderFiles(){
  if(isset($_GET['foldername']))
  {
    $path=\Yii::getAlias('@common').'\\upload\\' ;
      $foldername=$_GET['foldername'];
     $files=\yii\helpers\FileHelper::findFiles($path. $foldername,['recursive'=>FALSE]);
        return $files;
   }

  
  

}





    }





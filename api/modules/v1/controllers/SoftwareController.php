<?php

namespace api\modules\v1\controllers;
use yii\helpers\FileHelper;
use yii\rest\ActiveController;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class SoftwareController extends ActiveController
{
    public $modelClass = 'common\models\Software';   

    public function actionCreateDirectory()
    {
    	//$path=$_POST['']

    	$path=\Yii::getAlias('@common').'/upload/' ;
    	FileHelper::createDirectory($path);


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
    	$base_url=\Yii::getAlias('@common').'/'.'upload';
    	$directories = $this->expandDirectories($base_url);
		return $directories;

}




    }





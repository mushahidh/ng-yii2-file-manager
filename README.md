Yii2 Angular File Manager
==================================================														
														Modules : Create Folder, Upload Files ,  Download Files
														
Yii2 Angular File Manager is a platform where a user can create folders, upload both bulk and single file and download them.
```
project/
api/							//yii application folders
config/
modules/
runtime
web/

backend/        
assets/
config/
controllers/
models/
runtime/
tests/
views/
web/
codeception.yml

common/  
components/
config/
data/
mail/
models/
tests/
upload/
codeception.yml

console/
config/
controllers/
migrations/
models/
runtime/
tests/
codeception.yml
environments/							
dev/
prod/
index
vendor/							//Yii folders ends here
bin/
bower/
cebe/
composer/
ezyang/
phpspec/
swiftmailer/
yiisoft/
autoload
composer.json
composer.lock
init
init
LICENSE.MD
README.MD
requirements
yii
yii
...................................................................
Frontend/					Angular Folder
css/
fonts/
js/
nbproject/
partials/
scss/
index.html
```
																												
Directive used for file Uploading is ng-file-upload. For more details please visit (https://github.com/danialfarid/ng-file-upload)

Configuring Yii2
==================================================			

1. Open Command Line and clone the project from (https://github.com/mushahidh/ng-yii2-file-manager) to c:/wamp/www
```
git clone https://github.com/mushahidh/ng-yii2-file-manager.git
```
												
2.After cloning the project in the www go to project folder www/ng-yii2-file-manager and write 'compose update'.

3.After successfull compose update run 'php init'.

Configure Database
==================================================
					
					Go to www\ng-yii2-file-manager\common\data and copy open the 'sql' file, copy all the content. Create Database 
					
					database name : file_uploader
					
					paste the 'sql' file content in sql after choosing the database 'file_uploader'
					
																		
													
					
					
	
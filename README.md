Yii2 Angular File Manager
==================================================														

Modules : Create Folder, Upload Files ,  Download Files
														
Yii2 Angular File Manager is a platform where a user can create folders, upload both bulk and single file and download them.
											
																																							
Directive used for file Uploading is ng-file-upload. For more details please visit (https://github.com/danialfarid/ng-file-upload)

## Configuring Yii2
- Open Command Line and clone the project from (https://github.com/mushahidh/ng-yii2-file-manager) to 'www' Folder
```
git clone https://github.com/mushahidh/ng-yii2-file-manager.git
```
- After cloning the project in the 'www' folder go to project folder and write 
```
'compose update'
```
- After successfull compose update run 
```
'php init'.
```

## Configure Database					
- Go to www\ng-yii2-file-manager\common\data and copy open the 'sql' file, copy all the content. Create Database 
```
database name : file_uploader
```
paste the 'sql' file content in sql after choosing the database 'file_uploader'
```
																				
				project/
		api/
		backend/	
		common/  
		console/
		environments/							
		vendor/							//Yii folders ends here
		composer.json
		composer.lock
		init
		init
		LICENSE.MD
		README.MD
		requirements
		yii
		yii
		          ...................................................
		Frontend/					//Angular Folder
								

## How to access
- Go to http://localhost/ng-yii2-file-manager/frontend/index.html#/login 
- Username : admin , password : admin
											
						
					
					
	
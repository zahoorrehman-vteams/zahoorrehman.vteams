<?php

class JobController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';
	
	public function init()
	{
		Yii::import('application.extensions.phpmailer.JPhpMailer'); // Import php mail class	<br />
		Yii::import('ext.runactions.components.ERunActions');
		
		// Login Check
		if(Yii::app()->user->isGuest)
		$this->redirect('/site/login', true);
		
		// Validate Card Check 
		if(!Yii::app()->user->isGuest)
		{
			$model = UserProfile::model()->findByAttributes(array('user_id' => (int)Yii::app()->user->id));
			if($model)
			if($model->user->card_validated == 'No')
			{
				$this->redirect('/site/validatecard/'.$model->id,true);
			}
		}
		
		if(!Yii::app()->user->isGuest)
		{
			$package = Membership::getlatestInfo();
			if($package == "Buy" || $package == "Expire")
			{
				Yii::app()->user->setFlash('packageError','Select or Upgrade your Package for further use of Application.'.CHtml::link('Buy Package',array('/payment/buypackage'),array('class'=>'lnks')));
			}
		}
		
	}


	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','state','archive','payment','mailhouse','email','jobhome','startletterjob','startemailjob','startletterjob_2','start_job_templates','start_job_file','start_job_starttime','start_job_endtime','start_job_mail'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	

	/*
	* Mail House Function for Mail Hosue Sending  
	*/
	
	public function actionMailhouse($id)
	{
		if(!$id)
		$this->redirect('/job/index',true);
		
		$package = Membership::getlatestInfo();
		if($package == "Buy" || $package == "Expire")
		{
			Yii::app()->user->setFlash('error','Select or Upgrade your Package for further use of Application.'.CHtml::link('Buy Package',array('/payment/buypackage'),array('class'=>'lnks')));
		}
		if($package == "Live")
		{
				$model = $this->loadModel($id);
				if($model->job_type == 'Mail')
				{
						if($model->file_flag == 0)
						ERunActions::runScript('generatePDF',array('id'=>$model->id),$scriptPath=null);
						
						if($model->last_action_date != date('Y-m-d'))
						{
							ERunActions::runScript('sendSFTP',array('id'=>$model->id),$scriptPath=null);	
							@session_start();
							$model->last_action_date = date('Y-m-d');
							$model->save(false);
							Yii::app()->user->setFlash('success','Job is processed and Upload to Mail Hosue successfully.');
						}
						else if($model->last_action_date == date('Y-m-d'))
						{ 	@session_start();
							Yii::app()->user->setFlash('warn','Job is already uploaded to Mail Hosue.');
						}	
					
					
			 }
		}
		
		$this->redirect('/job/index',true);	
			
	}
	
	/*
	* Email Sending to End User
	*/
	public function actionEmail($id)
	{
		if(!$id)
		$this->redirect('/job/index',true);
		
		$package = Membership::getlatestInfo();
		if($package == "Buy" || $package == "Expire")
		{
			Yii::app()->user->setFlash('error','Select or Upgrade your Package for further use of Application.'.CHtml::link('Buy Package',array('/payment/buypackage'),array('class'=>'lnks')));
		}
		
		if($package == "Live")
		{
			$model = $this->loadModel($id);
			if($model->job_type == 'Email')
			{
				if($model->last_action_date != date('Y-m-d'))
				{
					ERunActions::runScript('emailSending',array('id'=>$model->id),$scriptPath=null);
					
					@session_start();
					$model->last_action_date = date('Y-m-d');
					$model->job_status = 'emailed';
					$model->save(false);
					Yii::app()->user->setFlash('success','Job is Emailed successfully.');
				}
				else if($model->last_action_date == date('Y-m-d'))
				{
					Yii::app()->user->setFlash('warn','Job is already Emailed.');
				}
		 }
		}
		$this->redirect('/job/index',true);
	}	
	
	/*
	* Payment Option of a Job, Check Packages Details and Redirection 
	*/
	public function actionPayment($id)
	{
		$package = Membership::getlatestInfo();
		if($package == "Buy" || $package == "Expire")
		{
			Yii::app()->user->setFlash('error','Select and Buy a Package First to Process a Job, Thank You.');
			$this->redirect('/payment/buypackage');
		}
		if($package == "Live")
		{
			$this->redirect('/payment/mailhousepayment/'.$id);	
		}
	}
	
	
	
	/*public function actionProcessemails($id)
	{
		$package = Membership::getlatestInfo();
		if($package == "Buy" || $package == "Expire")
		{
			Yii::app()->user->setFlash('error','Select and Buy a Package First to Process a Job, Thank You.');
			$this->redirect('/payment/buypackage');
		}
		if($package == "Live")
		{
			$this->redirect('/payment/mailhousepayment/'.$id);	
		}
		
		
		if(ERunActions::runScript('emailSending',array('id'=>$id),$scriptPath=null))
		   echo json_encode(array('msg'=>'DONE'));
	}*/
	
	/*
	* Perform Archived Un-archived
	*/
	public function actionArchive($id)
	{
		if(!Yii::app()->user->isGuest){
            $Id = (int) $_GET['id'];
            $model = $this->loadModel($Id);
			
            if ($model->job_status == 'archived')
				$model->job_status = 'queued';

            else
				$model->job_status = 'archived';
				
			$model->job_action = 'no-action';
            if ($model->save(false)){
                $this->redirect(array('index'));
            }
          }else{
                throw new CHttpException(403, Yii::app()->params['access']);  
          }
    	
	}
	
	
	/*
	* Perform Approved and Denided for Further Processing 
	*/
	public function actionState($id, $opt)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$opt = Yii::app()->request->getQuery('opt');
			$model = $this->loadModel($id);
			if($opt == 'approved')
			{
				$model->approved_time = Yii::app()->params['dateNTime'];
				$model->job_action = 'approved';
				if($model->job_type == 'Mail')
				{
					Yii::app()->user->setFlash('success','Job is Approved, please make Mail House payment to Schedule at Calendar for further processes.');
				}
				else
				{
					Yii::app()->user->setFlash('success','Job is Approved and Schedule at Calendar for further processes.');
					$model->show_on_calendar = 'active';
				}
			
			}	
			if($opt == 'denied')
			{
				$model->approved_time = Yii::app()->params['dateNTime'];
				$model->job_action = 'denied';
				Yii::app()->user->setFlash('success','Job is Denided for  further processes.');
			}
			$model->save(false);
			echo 1;
		}
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$templateModel = Usertemplates::model()->findByPk((int)$model->template_id);
		if($templateModel->html_contents)
		{	
			// Remove Setting Divs
			$array = array('#<div class="settings-button(.*?)</div>#', '#<div style="display: block;" class="settings-button(.*?)</div>#', '#<span (.*?)></span>#','#<em (.*?)></em>#','#<p style="text-align: center;"> </p>#');
			$templateModel->html_contents = preg_replace($array, '<so>', $templateModel->html_contents);
		
			$mPDF1 = Yii::app()->ePdf->mpdf('c');
			$mPDF1->SetAutoFont();
			$pdfName = strtoupper($model->job_title);
		
			$mPDF1->WriteHTML('<div style="font-smooth: auto; width:710px; height:900px;">'.$templateModel->html_contents.'</div>');	
			$mPDF1->Output($model->job_title.'.pdf', 'I');			
	
			$model->job_action = 'previewed';
			$model->save(false);
			Yii::app()->end();
	
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($id = 0)
	{
		// Job Form Model
		$formModel = new Job;
		if($id)
		$formModel = $formModel->findByPk((int)$id);
		// Ajax Validation
		$this->performAjaxValidation($formModel);
		// Grid Data 
		$gridModel=new Job('search');
		$gridModel->unsetAttributes();  // clear any default values
		if(isset($_GET['Job']))
		    $gridModel->attributes=$_GET['Job'];
			$gridModel->user_id = Yii::app()->user->id;
			
		
		// Job Form Post Functionality
		if(isset($_POST['Job']))
		{
			$formModel->attributes=$_POST['Job'];
			if($formModel->validate())
			{
				if($id || $formModel->file_flag == 1)
				{
					$formModel->job_action = 'no-action';
					$formModel->file_flag = 0;
					$formModel->last_action_date = '0000-00-00';
					if(is_file(Yii::app()->params['tmp'].'/'.strtoupper($formModel->job_title.'.zip')))
					{
						@unlink(Yii::app()->params['tmp'].'/'.strtoupper($formModel->job_title.'.zip'));	
					}
				}
				if($formModel->save())
				{
					
					Yii::app()->user->setFlash('success','Job is '.($id == 0 ? 'created' : 'updated').' successfully.');	
					$this->redirect(array('job/index/'.$formModel->id));
				}	
			}
		}
		
		$this->render('index',array('gridModel'=>$gridModel,'formModel'=>$formModel));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Job the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Job::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Job $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='job-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function addDates($givendate,$day=0,$mth=0,$yr=0) 
	{
              $cd = strtotime($givendate);
              $newdate = date('Y-m-d H:i:s', mktime(date('h',$cd),
              date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
              date('d',$cd)+$day, date('Y',$cd)+$yr));
              return $newdate;
        }
        
        public function actionJobhome()
        {
                $model=new LoginForm;
                $this->render('jobhome',array('model'=>$model));
        }
        public function actionStartletterjob()
        {
                $model=new Job;
                $this->render('startletterjob',array('formModel'=>$model));
        }
        public function actionStartemailjob()
        {
                $model=new Job;
                $this->render('startemailjob',array('formModel'=>$model));
        }
        /***********************
         * Prototype 2
         **********************/
        public function actionStartletterjob_2()
        {
                $model=new Job;
                $this->render('startletterjob_2',array('formModel'=>$model));
        }
        public function actionStart_job_templates()
        {
                $model=new Job;
                $this->render('start_job_templates',array('formModel'=>$model));
        }
        public function actionStart_job_file()
        {
                $model=new Job;
                $this->render('start_job_file',array('formModel'=>$model));
        }
        public function actionStart_job_starttime()
        {
                $model=new Job;
                $this->render('start_job_starttime',array('formModel'=>$model));
        }
        public function actionStart_job_endtime()
        {
                $model=new Job;
                $this->render('start_job_endtime',array('formModel'=>$model));
        }
        public function actionStart_job_mail()
        {
                $model=new Job;
                $this->render('start_job_mail',array('formModel'=>$model));
        }

}

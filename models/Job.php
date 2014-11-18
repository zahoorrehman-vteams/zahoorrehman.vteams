<?php

/**
 * This is the model class for table "tbl_job".
 *
 * The followings are the available columns in table 'tbl_job':
 * @property integer $id
 * @property integer $template_id
 * @property integer $user_id
 * @property string $job_title
 * @property string $job_action
 * @property string $job_status
 * @property integer $no_entries
 * @property integer $print_id
 * @property string $overall_cost
 * @property string $billing_date
 * @property string $billing_status
 * @property string $create_time
 * @property string $mail_time
 * @property string $approved_time
 * @property integer $file_flag
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Usertemplates $template
 * @property Jobemails[] $jobemails
 */
class Job extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Job the static model class
	 */
	public $logString = '';
	public $userPackage;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function defaultScope() {
		if(Yii::app()->user->isUser)
		return array(
            'condition' => 'user_id = '.YII::app()->user->getId(), // Customer can see only his orders
        );
		else
		return array();
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_job';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('template_id, job_title,job_type, print_id', 'required'),
			array('template_id, user_id, no_entries, print_id, file_flag', 'numerical', 'integerOnly'=>true),
			array('job_title', 'length', 'max'=>255),
			array('job_action, job_status', 'length', 'max'=>9),
			array('overall_cost', 'length', 'max'=>8),
			array('billing_status', 'length', 'max'=>6),
			
			array('start_time, end_time', 'date','format'=>'yyyy-m-d H:m:s'),
			array(
				  'start_time',
				  //'compare',
				  'default',
				  'value'=>date("Y-m-d H:i:s"),
				 // 'operator'=>'>', 
				
				  //'message'=>'{attribute} must be greater than "{compareValue}".'
				),
			array(
				  'end_time',
				  'compare',
				  'compareAttribute'=>'start_time',
				  'operator'=>'>=', 
				  'allowEmpty'=>false , 
				  'message'=>'{attribute} must be greater than "{compareValue}".'
				),
			
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, template_id, user_id, job_title, job_action, job_status, no_entries, print_id, overall_cost, billing_date, billing_status, create_time, mail_time, approved_time, file_flag,userPackage', 'safe', 'on'=>'search'),
			array('id, template_id, user_id, job_title, job_action, job_status, no_entries, print_id, overall_cost, billing_date, billing_status, create_time, mail_time, approved_time, file_flag,userPackage', 'safe', 'on'=>'searchjlog'),
		);
	
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'template' => array(self::BELONGS_TO, 'Usertemplates', 'template_id'),
			'jobemails' => array(self::HAS_MANY, 'Jobemails', 'job_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'template_id' => 'Template',
			'user_id' => 'User',
			'job_title' => 'Job Title',
			'job_action' => 'Job Action',
			'job_status' => 'Job Status',
			'no_entries' => 'No. Entries',
			'print_id' => 'Print',
			'overall_cost' => 'Overall Cost',
			'billing_date' => 'Billing Date',
			'billing_status' => 'Billing Status',
			'create_time' => 'Create Time',
			'mail_time' => 'Mail Time',
			'approved_time' => 'Approved Time',
			'file_flag' => 'File Flag',
			'job_type' => 'Type of Job',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('template_id',$this->template_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('job_title',$this->job_title,true);
		$criteria->compare('job_type',$this->job_type);
		$criteria->compare('job_action',$this->job_action,true);
		$criteria->compare('job_status',$this->job_status,true);
		$criteria->compare('no_entries',$this->no_entries);
		$criteria->compare('print_id',$this->print_id);
		$criteria->compare('overall_cost',$this->overall_cost,true);
		$criteria->compare('billing_status',$this->billing_status,true);
		
		$criteria->compare('DATE_FORMAT(`billing_date`,\'%m-%d-%Y\')',$this->billing_date,true);
		$criteria->compare('DATE_FORMAT(`create_time`,\'%m-%d-%Y\')',$this->create_time,true);
		$criteria->compare('DATE_FORMAT(`mail_time`,\'%m-%d-%Y\')',$this->mail_time,true);
		$criteria->compare('DATE_FORMAT(`approved_time`,\'%m-%d-%Y\')',$this->approved_time,true);
		
		/*$criteria->compare('billing_date',$this->billing_date,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('mail_time',$this->mail_time,true);
		$criteria->compare('approved_time',$this->approved_time,true);*/
		
		$criteria->compare('file_flag',$this->file_flag);
                 $criteria->order = 'create_time DESC';
                
		return new CActiveDataProvider($this, array(
			'pagination'=>array(
                                'pageSize'=>Yii::app()->params['page'],
                         ),
			'criteria'=>$criteria,
		));
	}
	
	public function searchjlog()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('template_id',$this->template_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('job_title',$this->job_title,true);
		$criteria->compare('job_action',$this->job_action,true);
		$criteria->compare('job_status',$this->job_status,true);
		$criteria->compare('no_entries',$this->no_entries);
		$criteria->compare('print_id',$this->print_id);
		$criteria->compare('overall_cost',$this->overall_cost,true);
		$criteria->compare('billing_status',$this->billing_status);
		
		$criteria->compare('DATE_FORMAT(`billing_date`,\'%m-%d-%Y\')',$this->billing_date,true);
		$criteria->compare('DATE_FORMAT(`create_time`,\'%m-%d-%Y\')',$this->create_time,true);
		$criteria->compare('DATE_FORMAT(`mail_time`,\'%m-%d-%Y\')',$this->mail_time,true);
		$criteria->compare('DATE_FORMAT(`approved_time`,\'%m-%d-%Y\')',$this->approved_time,true);
		
		/*$criteria->compare('billing_date',$this->billing_date,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('mail_time',$this->mail_time,true);
		$criteria->compare('approved_time',$this->approved_time,true);*/
		 $criteria->order = 'create_time DESC';
		$criteria->compare('file_flag',$this->file_flag);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
                                'pageSize'=>Yii::app()->params['page'],
                         ),
			'criteria'=>$criteria,
		));
	}
	
	// All Auto Save Settings before any Record Insert | Update
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
            	$this->user_id = Yii::app()->user->id;
				$this->job_action = 'no-action';
				$this->job_status = 'created';
				$this->create_time = Yii::app()->params['dateNTime'];
				$this->mail_time = '0000-00-00 00:00:00';
				$this->approved_time = '0000-00-00 00:00:00';
				$this->file_flag = 0;
				//$this->start_time = date('Y-m-d H:i:s');
				//$this->end_time = date('Y-m-d H:i:s');
            }
			/* 
			|====================|
			| Printig Calculation|
			|====================|
			*/
			// Per Print Cost (Color || Black & White)
			$costPerPrint = Mailhousesetting::model()->findByPk((int)$this->print_id)->cost_per_print;
		
			// Enterise (No. of Documents)		
			$upload_id = Usertemplates::model()->findByPk((int)$this->template_id)->upload_id;
			
			// Criteria For Upload File Data Lines
			$criteria=new CDbCriteria;		
			$criteria->compare('upload_id',(int)$upload_id);
			$uploadFileLines = UploadedData::model()->count($criteria);
			
			// No. of lines
			$this->no_entries = $uploadFileLines;
				
			// Calculate Cost
			if($this->job_type == 'Mail')
			$this->overall_cost = $costPerPrint * $uploadFileLines;
			else
			$this->overall_cost = 0;
			return true;
		}
		else
		return false;
	}
	
	
	
	/*
	* Process Job to PDF Generation 
	*/
	public function processJobs()
	{
		//ob_start();
		$start		= microtime(true);
		$tmp 		= Yii::app()->params['tmp'];
		$pdfs 		= Yii::app()->params['pdfs'];	
		$jobLogFile = time().uniqid().'.txt';
		$this->logString  = '';
		$pdfName = strtoupper($this->id.'_'.str_replace(' ','-',$this->job_title).'_'.Yii::app()->user->name);
		
		$logFileSource = fopen($tmp.'/'.$jobLogFile,'w');
		$this->logString.= "\n**============================== Start JOB (".$pdfName.") Processing ==============================**\r\n"; 
		
		if($this->template_id)
		{
			// Template ID 
			$templateModel = Usertemplates::model()->findByPk((int)$this->template_id);	
			$templateHTML = $templateModel->html_contents;
			
	
			$array = array('#<div class="settings-button(.*?)</div>#', '#<div style="display: block;" class="settings-button(.*?)</div>#','#<p style="text-align: center;"> </p>#');
			$templateHTML = preg_replace($array, '', $templateHTML);
			
			// Upload Keys
			$uploadKeys = Upload::model()->findByPk((int)$templateModel->upload_id)->file_columns;
			if($uploadKeys)
			{
				$uploadKeys = json_decode($uploadKeys);
				//Simplify Keys Values
				foreach($uploadKeys as $key=>$value)
				{
					$uploadKeys[$key] = '{{'.$value.'}}';
				}
				
				//LOAD PDF FILE
				$mPDF1 	= Yii::app()->ePdf->mpdf('c');
				$mPDF1->SetAutoFont();
				$nameWithPath = $pdfs.'/'.$pdfName;
				// Upload File File Data Lines (one line treated as record)
				$uploadData = UploadedData::model()->findAll('upload_id ='.$templateModel->upload_id);
				if($uploadData)
				{
					

					
					$i = 1;
					// Loop Through Upload Data Lines
					foreach($uploadData as $data)
					{
						// Data in Array Format 
						$data->file_data = json_decode($data->file_data);
						// Remove Empty SPAN and EM
						$data->file_data  = preg_replace('#<span (.*?)></span>#', '', $data->file_data);
						$data->file_data  = preg_replace('#<em (.*?)></em>#', '', $data->file_data);
						$templateHTML = str_replace($uploadKeys,$data->file_data,$templateHTML);
						
						// Append Contents into PDF
						$mPDF1->WriteHTML('<div style="font-smooth: auto; width:710px; height:900px;">'.$templateHTML.'</div>');		
						//if($i == 250) //Limited Records for testing
						//break;	  
						//else
						$i++;	
					}
					
					// Create a ZIP Archive	
					$zip = new ZipArchive();
					if ($zip->open($nameWithPath.'.zip', ZIPARCHIVE::CREATE)===TRUE) 
					{
						// Write PDF 
						$mPDF1->Output($nameWithPath.'.pdf', 'F');
						// Add PDF to ZIP 
						$zip->addFile($nameWithPath.'.pdf',$pdfName.'.pdf');
					}
					$zip->close();
					@unlink($nameWithPath.'.pdf');
					$this->logString.="@ Write Content to PDF File, Generate (".$i.") Documents and Stored in ZIP ** \r\n";
					
				}
				else
				$this->logString.="@ NO UPLOAD FILE DATA FIELDS Found ** \r\n"; 
			}
			else
			{
				$this->logString.="@ NO UPLOAD FILE KEY Found ** \r\n"; 
			}
		}
		else
		$this->logString.="@ No Template Found ** \r\n"; 
		
		$end=microtime(true);
		
		$this->file_flag = 1;
		$this->save(false);
		
		$this->logString.= "@ System Time: " .round(($end - $start),2)." seconds takes to complete execution. Process end Successfully.\r\n";
		
		fwrite($logFileSource,$this->logString);
		fclose($logFileSource);

		Yii::import('application.extensions.phpmailer.JPhpMailer'); // Import php mail class
		$mail = new JPhpMailer;
		$userEmail = $this->user->email;
		$userName = $this->user->username;
		$mail->AddAddress(trim($userEmail), trim($userName));
		$mail->SetFrom(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
		$mail->Subject = $this->job_title." Job Process log";
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
		
		$mail->AddAttachment($tmp.'/'.$jobLogFile);
		$contents = '<p>Hi '.trim(Yii::app()->user->name).',<br />
					 Your Job "'.strtoupper($this->job_title).'" is processed and ready to send Mail House for Mailing.<br />
					 A log file has been attached with this email according to statistics of your JOB.<br /><br />
					 <font color="#FF0000">It is system generated email please do not reply.</font><br />
					 <br /><br /><br />Thank you.</p>';
					 					
		$mail->MsgHTML($contents);
		if($mail->Send())
		{
			@unlink($tmp.'/'.$jobLogFile);
		}
		ob_clean();
		return true;			
	}
	
	
	public function sendMailHouse()
	{
		ob_start();
		Yii::import('application.extensions.phpmailer.JPhpMailer'); // Import php mail class	<br />
		$start		= microtime(true);
		$tmp 		= Yii::app()->params['tmp'];
		$pdfs 		= Yii::app()->params['pdfs'];	
		$jobLogFile = time().uniqid().'.txt';
		$this->logString  = '';
		$flag = false;
		
		$pdfName = strtoupper($this->id.'_'.str_replace(' ','-',$this->job_title).'_'.Yii::app()->user->name).'.zip';
		$logFileSource = fopen($tmp.'/'.$jobLogFile,'w');
		$this->logString.= "\n**============================== SFTP (".$pdfName.") Uploading ==============================**\r\n"; 
		
		$this->logString.=$pdfs.$pdfName."\r\n";
		//'mhouseEmail'
		//'mhouseName'
		if(file_exists($pdfs.'/'.$pdfName))
		{	
			
			if(Yii::app()->params['SFTP'])
			{
				try
				{
					Yii::app()->sftp->connect();
					// Parent Directory Selection
					$cur_dir = Yii::app()->sftp->getCurrentDir() . '/';
					$this->logString.="@ Connect to SFTP ** \r\n"; 
					
					if(Yii::app()->sftp->sendFile($pdfs.'/'.$pdfName, $pdfName))
					{
						$this->logString.="@ FILE uploaded to SFTP ** \r\n";
											$Encryp = new Encryption();
											$encryptInfo = $Encryp->safe_b64encode($this->user_id.'::'.$this->id);
						
						// Mail House Mail
						$mail = new JPhpMailer;
						$mail->AddAddress(trim(Yii::app()->params['mhouseEmail']), trim(Yii::app()->params['mhouseName']));
						$mail->Subject = 'New Job Uploaded (SFTP)';
						$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
						$contents = "<p>Hi ".Yii::app()->params['mhouseName'].",<br />
									 New Job with name of '".$this->job_title."', File with name of '".$pdfName."' has been uploaded to Mailing House at ".date(Yii::app()->params['cDetailTime']).".<br />
									 When Job is Mailed successfully, Kindly click on given link to Update our Application.<br /><br />
									 Link is: ".Yii::app()->createAbsoluteUrl('processes/mailsend?response='.$encryptInfo)."<br />
									 <font color='red'>It is system generated notification for your acknowledgement.</font><br /><br /><br />
									 Thank you.<p>";
						$mail->MsgHTML($contents);
						$mail->Send();
						$flag = true;
						
					}
					
				}
				catch(Exception $e)
				{
					$msg = '';
					foreach($e->getMessage() as $key => $message)
					{
						$msg.= $key.'  =>  '.$message;
					}
					$this->logString.="@ FILE upload to SFTP Errors: ".$msg."** \r\n";
					
				}   
			}
			if(Yii::app()->params['FTP'])
			{
				$ftp = Yii::app()->ftp;
				$ftp->currentDir();
				$this->logString.="@ Connect to FTP ** \r\n"; 
				//$ftp->put('GOOGLE.jpg', 'D:\43.jpg', FTP_BINARY);	
				if($ftp->put($pdfName, $pdfs.'/'.$pdfName, FTP_BINARY))
				{
					$this->logString.="@ FILE uploaded to FTP ** \r\n";
											$Encryp = new Encryption();
											$encryptInfo = $Encryp->safe_b64encode($this->user_id.'::'.$this->id);
						
						// Mail House Mail
						$mail = new JPhpMailer;
						$mail->AddAddress(trim(Yii::app()->params['mhouseEmail']), trim(Yii::app()->params['mhouseName']));
						$mail->Subject = 'New Job Uploaded (SFTP)';
						$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
						$contents = "<p>Hi ".Yii::app()->params['mhouseName'].",<br />
									 New Job with name of '".$this->job_title."', File with name of '".$pdfName."' has been uploaded to Mailing House at ".date(Yii::app()->params['cDetailTime']).".<br />
									 When Job is Mailed successfully, Kindly click on given link to Update our Application.<br /><br />
									 Link is: ".Yii::app()->createAbsoluteUrl('processes/mailsend?response='.$encryptInfo)."<br />
									 <font color='red'>It is system generated notification for your acknowledgement.</font><br /><br /><br />
									 Thank you.<p>";
						$mail->MsgHTML($contents);
						$mail->Send();
						$flag = true;
				}
				else
				$this->logString.="@ FILE upload to FTP Error ** \r\n";
			}
		}
		else
		$this->logString.="@ FILE (".$pdfName.") Not Found at Server** \r\n";
		
		$end=microtime(true);
		$this->logString.= "@ System Time: " .round(($end - $start),2)." seconds takes to complete execution. Uploading end Successfully.\r\n";
		
		fwrite($logFileSource,$this->logString);
		fclose($logFileSource);
		
		if($flag)
		{
			$mail = new JPhpMailer;
		
			$userEmail = $this->user->email;
			$userName = $this->user->username;
			$mail->AddAddress(trim($userEmail), trim($userName));
				
			$mail->Subject = 'New Job Uploaded (SFTP) Mail House';
			$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
			$mail->AddAttachment($tmp.'/'.$jobLogFile);
			$contents = "<p>Hi ".Yii::app()->user->name.",<br />
						 Your Job with name of '".$this->job_title."', File with name of '".$pdfName."' has been uploaded to Mailing House at ".date(Yii::app()->params['cDetailTime']).".<br />
						 A log file has been attached with this email according to statistics of your JOB.<br /><br />
						 <font color='red'>It is system generated notification for your acknowledgement.</font><br /><br /><br />
						 Thank you.<p>";
			$mail->MsgHTML($contents);
			if($mail->Send())
			{
				$this->job_status = 'archived';
				$this->save(false);
				
				@unlink($tmp.'/'.$jobLogFile);
			}
		}
		ob_clean();
		return true;		
	}
	
	
	public function emailSending() 
	{	
		ob_start();
		$start		= microtime(true);
		$tmp 		= Yii::app()->params['tmp'];
		$jobLogFile = time().uniqid().'.txt';
		$this->logString  = '';
		
		$logFileSource = fopen($tmp.'/'.$jobLogFile,'w');
		$this->logString.= "\n**============================== Start JOB (".$this->job_title.") Template Sending at Email ==============================**\r\n"; 
		
		if($this->template_id)
		{
			// Template ID 
			$templateModel = Usertemplates::model()->findByPk((int)$this->template_id);	
			$templateHTML = $templateModel->html_contents;
			
			$array = array('#<div class="settings-button(.*?)</div>#', '#<div style="display: block;" class="settings-button(.*?)</div>#','#<div rel="(.*?)</div>#');
			$templateHTML = preg_replace($array, array('',''), $templateHTML);
	
			// Upload Keys
			$uploadKeys = Upload::model()->findByPk((int)$templateModel->upload_id)->file_columns;
			if($uploadKeys)
			{
				$uploadKeys = json_decode($uploadKeys);
				//Simplify Keys Values
				foreach($uploadKeys as $key=>$value)
				{
					$uploadKeys[$key] = '{{'.$value.'}}';
				}
				//Repeative Process Email (Recursive func()) $this->no_entries
				$this->logString.=$this->processEmail($offSet = 0, $templateHTML, $uploadKeys, $templateModel->upload_id, $this->no_entries);	
			}
			else
			{
				$this->logString.="@ No UPLOAD FILE KEY Found ** \r\n"; 
			}
		}
		else
		$this->logString.="@ No Template Found ** \r\n"; 
		
		$end=microtime(true);
		$this->logString.= "@ System Time: " .round(($end - $start),2)." seconds takes to complete execution. Process end Successfully.\r\n";
		
		fwrite($logFileSource,$this->logString);
		fclose($logFileSource);
		
		Yii::import('application.extensions.phpmailer.JPhpMailer'); // Import php mail class
		$mail = new JPhpMailer;
		
		$userEmail = $this->user->email;
		$userName = $this->user->username;
		$mail->AddAddress(trim($userEmail), trim($userName));
		
		$mail->SetFrom(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
		$mail->Subject = $this->job_title." Job Process log";
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
		
		$mail->AddAttachment($tmp.'/'.$jobLogFile);
		$contents = '<p>Hi '.trim(Yii::app()->user->name).',<br />
					 Your Job "'.strtoupper($this->job_title).'" has been processed and emails have been sent to corresponding recipients.<br />
					 A log file has been attached with this email according to statistics of your JOB.<br /><br />
					 <font color="#FF0000">It is system generated email please do not reply.</font><br />
					 <br /><br /><br />Thank you.</p>';
					 					
		$mail->MsgHTML($contents);
		if($mail->Send())
		{
			@unlink($tmp.'/'.$jobLogFile);
		}
		ob_clean();
		return true;			
	}
		
	public $innerString = '';	
	protected function processEmail($offSet = 0, $templateHTML, $uploadKeys, $template_upload_id, $noEntries)
	{
		$Criteria = new CDbCriteria();
		$Criteria->offset = $offSet; // Off Set Records want to select 
		$Criteria->limit = 100; // Limit 10, 20, 50, 100 etc
		$Criteria->condition = 'upload_id ='.$template_upload_id;
		$uploadData = UploadedData::model()->findAll($Criteria);
		Yii::import('application.extensions.phpmailer.JPhpMailer'); // Import php mail class
		if($uploadData)
		{
		
			// Loop Through Upload Data Lines
			foreach($uploadData as $data)
			{
				// Data in Array Format 
				$data->file_data = json_decode($data->file_data);
				
				print_r($data->file_data);
				
				preg_match("/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/i", implode(' ',$data->file_data), $matches);
				if($matches)
				{
					if($matches[0])
					{
						// Remove Empty SPAN and EM
						$data->file_data  = preg_replace('#<span (.*?)></span>#', '', $data->file_data);
						$data->file_data  = preg_replace('#<em (.*?)></em>#', '', $data->file_data);
						$templateUpdateHTML = str_replace($uploadKeys,$data->file_data,$templateHTML);
						$mail = new JPhpMailer;
						$mail->SetFrom(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
						$mail->AddAddress(trim($matches[0]));
						$mail->Subject = $this->job_title;
						$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
						$content = '<html xmlns="http://www.w3.org/1999/xhtml">
									<head>
									<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
									<title>'.$this->job_title.'</title>
									</head>								
									<body>
									'.$templateUpdateHTML.'
									</body>
									</html>';
									
										
									
						$mail->MsgHTML($content);
						if($mail->Send()) 
						{
							$this->innerString.="@ Email Sent to:".$matches[0]." ** \r\n";	
						}
						else
						{
							$this->innerString.="@ Email Sending Failed to:".$matches[0]." **\r\n";							
						}
					}
					else
					{
						$this->innerString.="@ No Email Found to send Template **\r\n"; 
					}
				}	
				//if($offSet == 0)
				//break;	  
				//else
				$offSet++;	
			}
			if($offSet < $noEntries)
			{
				$this->innerString.="@ Sleep Call for System Rest ".time()." **\r\n"; 
				sleep(60); // 60 Seconds Sleep
				$this->innerString.="@ Wake Up Call For System ".time()." **\r\n"; 
				$this->processEmail($offSet, $templateHTML, $uploadKeys, $template_upload_id, $noEntries);	
			}	
		}
		else
		$this->innerString.="@ No UPLOAD FILE DATA FIELDS Found ** \r\n";
	
		return $this->innerString;
	}
	
	
	// After Find convert Time from UNIX to Normal 
	/*public function afterFind()
	{
		$this->start_time = date('Y-m-d H:i:s',$this->start_time);
		$this->end_time = date('Y-m-d H:i:s',$this->end_time);
	}*/
	
	// Get Template 
	
	public function getTemplate()
	{
		return Usertemplates::model()->findByPk((int)$this->template_id);
	}
	
	// Get  Printers Details (Envolpe Sizes & Prices)
	public function getPrinter()
	{
		return Mailhousesetting::model()->findByPk((int)$this->print_id);
	}
	
	protected function AfterFind()
	{
		$this->userPackage = Packages::getUserPacakge(Yii::app()->user->id);	
	}
	
	
}
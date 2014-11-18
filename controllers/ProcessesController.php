<?php 
class ProcessesController extends Controller
{
    public $Encryp;
    public $Mail;
    public function init()
    {   
        $this->Encryp = new Encryption();
        Yii::import('application.extensions.phpmailer.JPhpMailer'); // Import php mail class
        $this->Mail = new JPhpMailer;
    }
	// Mail house back to APP Response
	public function actionMailsend()
	{
           $getResponse = Yii::app()->request->getQuery('response');
           if($getResponse)
            $encrypInfo = $getResponse;
           else     
           {
                $queryString = Yii::app()->request->getQueryString();
                parse_str($queryString, $encrypInfoArray);
                if($encrypInfoArray['response'])
                {
                   $encrypInfo = $encrypInfoArray['response'];
                }    
           }
           
           if($encrypInfo)
           {
              $responseValues = $this->Encryp->safe_b64decode(trim($encrypInfo));
              $responseValues = explode('::',$responseValues);
              $model = Job::model()->findByPk((int)$responseValues[1]);
              if($model)
              {
                  $model->job_status = 'mailed';
                  $model->mail_time = Yii::app()->params['dateNTime'];
		  $model->save(false);
                  
                  // Send Email to End User
                  $userEmail = $model->user->email;
                  $userName = $model->user->username;
                  
                    $this->Mail->AddAddress(trim($userEmail), trim($userName));
                    $this->Mail->SetFrom(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
                    $this->Mail->Subject = $model->job_title." Job has Mailed";
                    $this->Mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically

                    $contents = '<p>Hi '.trim(Yii::app()->user->name).',<br />
                                             Your Job "'.strtoupper($model->job_title).'" has been Mailed to all of your customers from Mail House.<br />
                                             <font color="#FF0000">It is system generated email please do not reply.</font><br />
                                             <br /><br /><br />Thank you.</p>';

                    $this->Mail->MsgHTML($contents);
                    if($this->Mail->Send())
                    {
                       Yii::app()->user->setFlash('success','Job record is updated and Notificatin Email has been sent to ower. Thank you for your Time.');  
                    }
              }
              else
              Yii::app()->user->setFlash('error','Key Missing, please copy complete link into your browser, Thank you');    
            
           }
           else
            Yii::app()->user->setFlash('error','Key Missing, please copy complete link into browser Thanks');
           
            $this->render('index');
	}
		
	public function actionCancel()
	{
		$mainFile   = fopen(Yii::app()->params['tmp'].'/Cancel.txt','w');
		$fileString = "\n **============================= Call File Time: ".date('h:i:s')." ============================= \n";
		fwrite($mainFile,$fileString);
		 $queryString = Yii::app()->request->getQueryString();
		echo "<pre>";
		var_dump($queryString);	
		parse_str($queryString, $output);
		if($output)
		{
			$data = '';
			foreach($output as $key=>$value)
			{
				$data.= "\n Key :: ".$key. " || Value :: ".$value."";
			}
			
			$data.= "Logged In User Information are: ".Yii::app()->user->id." User name : ". Yii::app()->user->name;
			
			$fileString = "\n =============================  \n Data: ".$data." : Time: ".date('h:i:s')." \n =============================** \n";
			fwrite($mainFile,$fileString);
		}
		fclose($mainFile);
		print_r($output);
		echo "Working in Progress.....!";
		echo "</pre>";		
	}	
	
	/*
	* Dwolla Return From Package Payment
	*/
	public function  actionDwollapackage()
	{
			// Dwolla Log Entry Start Open
			$logFile = fopen(Yii::app()->params['logs'].'/Dwolla-transication-log.txt','a+');
			
            $queryString = Yii::app()->request->getQueryString();
            parse_str($queryString, $output); 
            if(is_array($output) && array_key_exists('signature',$output))
            {    
                if($output['signature'] && $output['orderId'] && $output['checkoutId'] && $output['status'] == 'Completed')
                {
                    $orderID = $this->Encryp->safe_b64decode(trim($output['orderId']));
                    $orderID = explode('::',$orderID); 
                    $loadPackage = Packages::model()->findByPk((int)$orderID[0]);
                    $userModel = User::model()->findByPk((int)$orderID[2]);
					
					// Add Signature Line
				   foreach($output as $key => $value)
				   {
					 fwrite($logFile, $key."=>".$value."\r\n");
				   }

                    if($loadPackage)
                    {
                        /* MemberShip */
                        $memberModel = new Membership();
                        $criteria=new CDbCriteria;
                        $criteria->condition = 'user_id = '.(int)$orderID[2];
                        $criteria->addCondition('package_id = '.(int)$orderID[0], 'AND');
                        $criteria->addCondition('DATE_FORMAT(`orderDate`,\'%m-%d-%Y\') = "'.date('m-d-Y').'"');
                        $foundData = $memberModel->find($criteria);
                        
                        if(!$foundData)
                        {
                            $memberModel->user_id = $userModel->id;
                            $memberModel->package_id = $loadPackage->id;
                            $memberModel->amount = $loadPackage->package_price;
                            $memberModel->join_date  = $memberModel->orderDate = Yii::app()->params['dateNTime'];
                            $memberModel->status = 'Paid';

                            if($loadPackage->package_duration == 'Single')
                            $memberModel->expiry_date = Yii::app()->params['dateNTime'];

                            if($loadPackage->package_duration == 'Monthly')
                            $memberModel->expiry_date = $this->addDates(Yii::app()->params['dateNTime'],0,1,0);

                            if($loadPackage->package_duration == 'Yearly')
                            $memberModel->expiry_date = $this->addDates(Yii::app()->params['dateNTime'],0,0,1);
                            $memberModel->save(false);

                            $userModel->userProfiles->membership_id = $memberModel->id;
                            $userModel->userProfiles->save(false);

                            try
                            {
                                /* EMail Send to End User */
                                $this->Mail->AddAddress(trim($userModel->email), trim($userModel->username));
                                $this->Mail->SetFrom(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
                                $this->Mail->Subject =  'Package Payment Notification';
                                $this->Mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                                $contents = '<p>Hi '.trim($userModel->username).',<br /> You have successfully Paid for selected Package "'.$loadPackage->package_name.'". Your transication and Package Details are:<br /><b>Signature :</b> '.$output['signature'].'<br />
                                <b>Package Name:</b> '.$loadPackage->package_name.'<br />
                                <b>Package Duration:</b> '.$loadPackage->package_duration.'<br />
                                <b>Expiry Date:</b> '.$memberModel->expiry_date.'<br />
                                <font color="#FF0000">It is system generated email please do not reply.</font><br />    
                                <br /><br />Thank you.';

                                $this->Mail->MsgHTML($contents);
                                $this->Mail->Send();
                                
                                /* EMail Send To Administrator */
                                $this->Mail->AddAddress(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
                                $this->Mail->SetFrom(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
                                $this->Mail->Subject = $loadPackage->package_name." Payment Notification";
                                $this->Mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                                $contents = '<p>Hi '.trim(Yii::app()->params['adminName']).',<br /> '.trim($userModel->username).' has successfully Paid for selected Package "'.$loadPackage->package_name.'". His transication and Package Details are:<br /><b>Signature :</b> '.$output['signature'].'<br />
                                <b>Package Name:</b> '.$loadPackage->package_name.'<br />
                                <b>Package Duration:</b> '.$loadPackage->package_duration.'<br />
                                <b>Expiry Date:</b> '.$memberModel->expiry_date.'<br />
                                <font color="#FF0000">It is system generated email please do not reply.</font><br />    
                                <br /><br />Thank you.';
                                $this->Mail->MsgHTML($contents);
                                $this->Mail->Send();

                                $billModel = new BillingHistory();
                                $billModel->payment_for_title = $loadPackage->package_name;
                                $billModel->payment_for_id = $loadPackage->id;
                                $billModel->payment_for = 'Package';			
                                $billModel->payment_by = 'Dwolla';
                                $billModel->transiction_id = $output['signature'];
                                $billModel->transiction_date = date(Yii::app()->params['cDetailTime']);
                                $billModel->transiction_amount = $loadPackage->package_price;
                                $billModel->transiction_status = 'success';
                                $billModel->user_id = $userModel->id;
                                $billModel->save(false);
                                Yii::app()->user->setFlash('success', 'Package Payment is made successfully. Check your email for more details. Thank you');
							
							  
                            } catch (phpmailerException $e) {
							  
							  fwrite($logFile, "error => ".$e->errorMessage()."\r\n");
							  
                              Yii::app()->user->setFlash('error', $e->errorMessage());

                            } catch (Exception $e) {
							
							   fwrite($logFile, "error => ".$e->getMessage()."\r\n");
							   
                              Yii::app()->user->setFlash('error', $e->getMessage());
                            }
                        }   
                        else 
                        {
                               Yii::app()->user->setFlash('error', 'You aleardy made payment for this Package "'.$loadPackage->package_name.'" at '.date(Yii::app()->params['cDetailTime'],strtotime($foundData->orderDate)));  
                        }
                    }
                }
                else
                Yii::app()->user->setFlash('error', 'Package Payment cannot be made at this time, Error Message: '.$output['error'].', Description: '.$output['status']);     
            }
            else
            {
			  fwrite($logFile, "Error Message => Response Key Description are missing \r\n");
              Yii::app()->user->setFlash('error', 'Package Payment cannot be made at this time, Error Message: Response Key, and Description are missing.'); 
            }
			
			
			
			fclose($logFile);
			// Log Entry Close
		
            $this->render('index');
	}
	
	/*
	* Dwolla Return From Mail House Payment
	*/
	
	public function  actionDwollamailhouse()
	{
			
			// Dwolla Log Entry Start Open
			$logFile = fopen(Yii::app()->params['logs'].'/Dwolla-transication-log.txt','a+');
	
            $queryString = Yii::app()->request->getQueryString();
            parse_str($queryString, $output); 
            if(is_array($output) && array_key_exists('signature',$output))
            {
                if($output['signature'] && $output['orderId'] && $output['checkoutId'] && $output['status'] == 'Completed')
                {
                    $orderID   = $this->Encryp->safe_b64decode(trim($output['orderId']));
                    $orderID   = explode('::',$orderID);
                    $loadJob   = Job::model()->findByPk((int)$orderID[0]);//(int)$orderID[0]);
                    $userModel = User::model()->findByPk((int)$orderID[2]);
					
					// Add Signature Line
					   foreach($output as $key => $value)
					   {
						 fwrite($logFile, $key."=>".$value."\r\n");
					   }
					
                
                    if($loadJob)
                    {
                        try
                        {
                            //Save Payments
                            $loadJob->billing_date   = Yii::app()->params['dateNTime'];
                            $loadJob->billing_status = 'paid';
                            $loadJob->save(false);
                            
                            /* EMail Send to End User */
                            $this->Mail->AddAddress(trim($userModel->email), trim($userModel->username));
                            $this->Mail->SetFrom(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
                            $this->Mail->Subject =  'Mail House Payment Notification';
                            $this->Mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                            $contents = '<p>Hi '.trim($userModel->username).',<br /> You have successfully Paid ('.$loadJob->overall_cost.')$ for Mail house. 
                                     Your transication details are:<br /><b>Transaction ID:</b> '.$output['signature'].'<br />
                                     <b>Transication Date:</b> '.date(Yii::app()->params['cDetailTime']).'<br />
                                     <br /><br />Thank you.';

                            $this->Mail->MsgHTML($contents);
                            $this->Mail->Send();
                            
                            /* EMail Send to Administrator */
                            $this->Mail->AddAddress(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
                            $this->Mail->SetFrom(trim(Yii::app()->params['adminEmail']), trim(Yii::app()->params['adminName']));
                            $this->Mail->Subject =  'Mail House Payment Notification';
                            $this->Mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                            $contents = '<p>Hi '.trim(Yii::app()->params['adminName']).',<br /> '.trim($userModel->username).' has successfully Paid ('.$loadJob->overall_cost.')$ for Mail house. 
                                     Your transication details are:<br /><b>Transaction ID:</b> '.$output['signature'].'<br />
                                     <b>Transication Date:</b> '.date(Yii::app()->params['cDetailTime']).'<br />
									  <b>Schedule Start Time :</b> '.$loadJob->start_time.'<br />
				 						<b>Schedule End Time:</b> '.$loadJob->end_time.'<br />
                                     <br /><br />Thank you.';

                            $this->Mail->MsgHTML($contents);
                            $this->Mail->Send();
                            

                            $billModel = new BillingHistory();

                            $criteria=new CDbCriteria;
                            $criteria->condition = 'payment_for_id = '.(int)$loadJob->id;
                            $criteria->addCondition('user_id = '.(int)$userModel->id, 'AND');
                            $criteria->addCondition('DATE_FORMAT(`transiction_date`,\'%m-%d-%Y\') = "'.date('m-d-Y').'"');
                            $foundData = $billModel->find($criteria);

                            if(!$foundData)
                            {
                                $billModel->payment_for_title = $loadJob->job_title;
                                $billModel->payment_for_id = $loadJob->id;
                                $billModel->payment_for = 'Mail House';			
                                $billModel->payment_by = 'Dwolla';
                                $billModel->transiction_id = $output['signature'];
                                $billModel->transiction_date = date(Yii::app()->params['cDetailTime']);
                                $billModel->transiction_amount = $loadJob->overall_cost;
                                $billModel->transiction_status = 'success';
                                $billModel->user_id = $userModel->id;
                                $billModel->save(false);
                            }

                            Yii::app()->user->setFlash('success', 'Mail House Payment is made successfully. Check your email for more details. Thank you');
                        } catch (phpmailerException $e) {
						
						   fwrite($logFile, "error => ".$e->errorMessage()."\r\n");
                          Yii::app()->user->setFlash('error', $e->errorMessage());

                        } catch (Exception $e) {
						   fwrite($logFile, "error => ".$e->getMessage()."\r\n");
                          Yii::app()->user->setFlash('error', $e->getMessage());
                        }
                   }
                   else { Yii::app()->user->setFlash('error', 'Mail House Payment cannot be made at this time, please try again later.'); }
            }
                else
				
				 fwrite($logFile, "error => ".$output['error'].', Description: '.$output['status']."\r\n");
				
                Yii::app()->user->setFlash('error', 'Mail House Payment cannot be made at this time, Error Message: Response Key, and Description are missing.');     
            }
            else
            {
				 fwrite($logFile, "error => Response Key, and Description are missing \r\n");
				 
               Yii::app()->user->setFlash('error', 'Package Payment cannot be made at this time, Error Message: Response Key, and Description are missing.'); 
            }
            
            $this->render('index');
	}
        
	protected function addDates($givendate,$day=0,$mth=0,$yr=0) 
	{
		$cd = strtotime($givendate);
		$newdate = date('Y-m-d H:i:s', mktime(date('h',$cd),
		date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
		date('d',$cd)+$day, date('Y',$cd)+$yr));
		return $newdate;
	}
}
<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $create_time
 * @property string $last_accessed
 * @property integer $status
 * @property string $card_validated
 * @property string $access_token
 * @property string $activationcode
 * @property string $licence
 *
 * The followings are the available model relations:
 * @property Joblogs[] $joblogs
 * @property Membership[] $memberships
 * @property Template[] $templates
 * @property Upload[] $uploads
 * @property UserProfile[] $userProfiles
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	
	 // holds the password confirmation password
    public $confirm_password;
	public $new_password;
	public $verifyCode;
	
	
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, email, password', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>80),
			array('email, password, salt', 'length', 'max'=>100),
			array('card_validated', 'length', 'max'=>3),
			array('access_token, activationcode', 'length', 'max'=>255),
			array('licence', 'length', 'max'=>15),
			
			
			array('username, email', 'unique'),
			array('email', 'email'),
			array('username', 'match', 'pattern' => '/^([0-9a-zA-Z ]+)$/'),
			array('password','length', 'min'=>'6', 'max'=>15),
			
			
			array('password, new_password, confirm_password', 'required', 'on' => 'changepassword'),
			array('confirm_password', 'compare', 'compareAttribute'=>'new_password', 'on' => 'changepassword'),
			
			array('password, confirm_password', 'required', 'on' => 'register'),
			array('confirm_password', 'compare', 'compareAttribute'=>'password', 'on' => 'register'),
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(),'on' => 'register'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, email, password, salt, create_time, last_accessed, status, card_validated, access_token, activationcode, licence', 'safe', 'on'=>'search'),
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
				
			//'templates' => array(self::HAS_MANY, 'Template', 'user_id'),
			//'uploads' => array(self::HAS_MANY, 'Upload', 'user_id'),
			//'userProfiles' => array(self::HAS_ONE, 'UserProfile', 'user_id'),
			//'joblogs' => array(self::HAS_MANY, 'Joblogs', 'user_id'),
			
			'job' => array(self::HAS_MANY, 'Job', 'user_id'),
			'memberships' => array(self::HAS_MANY, 'Membership', 'user_id'),
			'templates' => array(self::HAS_MANY, 'Template', 'user_id'),
			'uploads' => array(self::HAS_MANY, 'Upload', 'user_id'),
			'userProfiles' => array(self::HAS_ONE, 'UserProfile', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'email' => 'Email',
			'password' => 'Password',
			'salt' => 'Salt',
			'create_time' => 'Create Time',
			'last_accessed' => 'Last Accessed',
			'status' => 'Status',
			'card_validated' => 'Card Validated',
			'access_token' => 'Access Token',
			'activationcode' => 'Activationcode',
			'licence' => 'Licence',
			
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		//$criteria->compare('create_time',$this->create_time,true);
		//$criteria->compare('last_accessed',$this->last_accessed,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('card_validated',$this->card_validated,true);
		$criteria->compare('access_token',$this->access_token,true);
		$criteria->compare('activationcode',$this->activationcode,true);
		$criteria->compare('licence',$this->licence,true);
		
		$criteria->compare('DATE_FORMAT(`create_time`,\'%m-%d-%Y\')',$this->create_time,true);
		$criteria->compare('DATE_FORMAT(`last_accessed`,\'%m-%d-%Y\')',$this->last_accessed,true);
                
                $criteria->order = 'id DESC';
                
		return new CActiveDataProvider($this, array(
			'pagination'=>array(
                                'pageSize'=>Yii::app()->params['page'],
                         ),
			'criteria'=>$criteria,
		));
	}
	
	 public function validatePassword($password)
	 {
		 return $this->hashPassword($password,$this->salt) === $this->password;
	
	 }
	  /**
	 * Create activation code.
	 * @param string email
	 */
	 
	 public function generateActivationCode($email){
	 
		 return sha1(mt_rand(10000, 99999).time().$email);
	 }
	 
	/**
	 * Generates the password hash.
	 * @param string password
	 * @param string salt
	 * @return string hash
	 */
	 public function hashPassword($password,$salt)
	 {
		 return md5($salt.$password);
	 }
	 
	/**
	 * Generates a salt that can be used to generate a password hash.
	 * @return string the salt
	 */
	 public function generateSalt()
	 {
		 return uniqid('',true);
	 }

	 protected function beforeSave()
	 {
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
                $newSalt = $this->generateSalt();
				$this->password = $this->hashPassword($this->password,$newSalt);
				$this->salt = $newSalt;
				//$this->status = 0;
				$this->create_time = Yii::app()->params['dateNTime'];
            }
			
			
           return true;
		}
		else
		  return false;
             return true;
	 }
	 
	 
	public function getUsers($user_id = 0)
	{
		return CHtml::listData(self::model()->findAll(($user_id > 0 ? 'id='.$user_id: '1=1')),'id','username');
	}
	
	public function getUser($user_id)
	{
		return self::model()->findByPk((int)$user_id);
	}
}
<?php

/**
 * This is the model class for table "tbl_user_profile".
 *
 * The followings are the available columns in table 'tbl_user_profile':
 * @property integer $id
 * @property integer $user_id
 * @property integer $membership_id
 * @property string $first_name
 * @property string $last_name
 * @property string $billing_address
 * @property string $billing_county
 * @property integer $billing_city
 * @property integer $billing_state
 * @property string $billing_zip_code
 * @property string $contact_name
 * @property string $contact_title
 * @property string $uemail
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $phone
 * @property string $fax
 * @property string $date_created
 * @property string $date_modified
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserProfile extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserProfile the static model class
	 */
	
	public $billing_address1;
	public $creditCardNumber;
	public $creditCardType;
	public $expDateMonth;
	public $expDateYear;
	public $CVV;
	public $package_id;
	
	
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*public function defaultScope() {
		if(Yii::app()->user->isUser)
		return array(
            'condition' => 'user_id = '.YII::app()->user->getId(),
        );
		else
		return array();
    }*/

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			
			array('first_name, last_name, billing_address, billing_county, billing_city, billing_state, billing_zip_code, contact_name, contact_title, address1, city, state, zip, phone, fax', 'required', 'on'=>'manageprofile'),
			array('user_id, first_name, last_name, billing_address, billing_county, billing_city, billing_state, billing_zip_code,creditCardNumber,creditCardType, expDateMonth,expDateYear,CVV', 'required', 'on' => array('Validatecard')),
			array('user_id, membership_id', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, contact_name, uemail', 'length', 'max'=>100),
			array('billing_address, billing_county', 'length', 'max'=>255),
			array('billing_zip_code, zip', 'length', 'max'=>10),
			array('contact_title, phone, fax', 'length', 'max'=>15),
			array('address1, address2', 'length', 'max'=>150),
			array('address3, city, state', 'length', 'max'=>20),
			array('package_id', 'required', 'on' => 'buypackage'),
			
			
			
			
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, membership_id, first_name, last_name, billing_address, billing_county, billing_city, billing_state, billing_zip_code, contact_name, contact_title, uemail, address1, address2, address3, city, state, zip, phone, fax, date_created, date_modified', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'membership_id' => 'Membership',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'billing_address' => 'Billing Address',
			'billing_county' => 'Billing County',
			'billing_city' => 'Billing City',
			'billing_state' => 'Billing State',
			'billing_zip_code' => 'Billing Zip Code',
			'contact_name' => 'Contact Name',
			'contact_title' => 'Contact Title',
			'uemail' => 'Uemail',
			'address1' => 'Address1',
			'address2' => 'Address2',
			'address3' => 'Address3',
			'city' => 'City',
			'state' => 'State',
			'zip' => 'Zip',
			'phone' => 'Phone',
			'fax' => 'Fax',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
			
			'billing_address1' =>'Billing address 2',
			
			
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('membership_id',$this->membership_id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('billing_address',$this->billing_address,true);
		$criteria->compare('billing_county',$this->billing_county,true);
		$criteria->compare('billing_city',$this->billing_city);
		$criteria->compare('billing_state',$this->billing_state);
		$criteria->compare('billing_zip_code',$this->billing_zip_code,true);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('contact_title',$this->contact_title,true);
		$criteria->compare('uemail',$this->uemail,true);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('address3',$this->address3,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_modified',$this->date_modified,true);

		return new CActiveDataProvider($this, array(
		'pagination'=>array(
                                'pageSize'=>Yii::app()->params['page'],
                         ),
			'criteria'=>$criteria,
		));
	}
	
	 protected function beforeSave()
	 {
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->date_created =  $this->date_modified =  Yii::app()->params['dateNTime'];
            }
			else
			{
				$this->date_modified =  Yii::app()->params['dateNTime'];
			}
			
			
           return true;
		}
		else
		  return false;
             return true;
	 }
	
}
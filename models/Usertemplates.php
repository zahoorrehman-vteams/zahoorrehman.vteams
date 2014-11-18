<?php

/**
 * This is the model class for table "tbl_user_templates".
 *
 * The followings are the available columns in table 'tbl_user_templates':
 * @property integer $id
 * @property integer $user_id
 * @property integer $upload_id
 * @property string $title
 * @property string $short_description
 * @property string $html_contents
 * @property string $pre_define_template
 * @property integer $pretemplate_id
 * @property string $template_states
 * @property string $template_category
 * @property string $created_on
 * @property string $modified_on
 */

class Usertemplates extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserTemplates the static model class
	 */
    public $username;
	
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
		return 'tbl_user_templates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('user_id, upload_id, title, html_contents, pretemplate_id, created_on, modified_on', 'required'),
                        array('user_id,  title, html_contents, pretemplate_id, created_on, modified_on', 'required'),
                        // array(' html_contents, created_on, modified_on', 'required'),
                        // array(' html_contents, created_on, modified_on','user_id','upload_id','title','short_description', 'required'),
			array('user_id,  pretemplate_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>150),
			array('pre_define_template', 'length', 'max'=>3),
			array('template_states', 'length', 'max'=>11),
			array('template_category', 'length', 'max'=>5),
                        array('created_on,modified_on','default', 'value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
			array('id, user_id, title, short_description, html_contents, pre_define_template, pretemplate_id, template_states, template_category, created_on, modified_on', 'safe', 'on'=>'search'),
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
			'jobs' => array(self::HAS_MANY, 'Job', 'template_id'),
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
			'upload_id' => 'Upload',
			'title' => 'Title',
			'short_description' => 'Short Description',
			'html_contents' => 'Html Contents',
			'pre_define_template' => 'Pre Define Template',
			'pretemplate_id' => 'Pretemplate',
			'template_states' => 'Template States',
			'template_category' => 'Template Category',
			'created_on' => 'Created On',
			'modified_on' => 'Modified On',
            'username' => 'Username',
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
		$criteria->compare('upload_id',$this->upload_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('short_description',$this->short_description,true);
		$criteria->compare('html_contents',$this->html_contents,true);
		$criteria->compare('pre_define_template',$this->pre_define_template,true);
		$criteria->compare('pretemplate_id',$this->pretemplate_id);
		$criteria->compare('template_states',$this->template_states,true);
		$criteria->compare('template_category',$this->template_category,true);
		//$criteria->compare('created_on',$this->created_on,true);
		//$criteria->compare('modified_on',$this->modified_on,true);
                
                $criteria->compare('DATE_FORMAT(`created_on`,\'%m-%d-%Y\')',$this->created_on,true);
		$criteria->compare('DATE_FORMAT(`modified_on`,\'%m-%d-%Y\')',$this->modified_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getTemplates($user_id = 0)
	{
		return CHtml::listData(self::model()->findAll(($user_id > 0 ? 'user_id='.$user_id: '1=1')),'id','title');	
	}
	
	
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
            	$this->user_id = Yii::app()->user->id;
            }
		}
		return true;
	}
	
	
}
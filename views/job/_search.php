<?php
/* @var $this JobController */
/* @var $model Job */
/* @var $form CActiveForm */
?>


<div class="wide form">
  <?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
  <div class="row"> <?php echo $form->label($model,'id'); ?> 
  					<?php echo $form->textField($model,'id', array('class'=>'input field')); ?> 
  </div>
  <div class="row"> <?php echo $form->label($model,'template_id'); ?> 
  					<?php echo $form->dropDownList($model,'template_id',Usertemplates::getTemplates(Yii::app()->user->id),array('empty'=>'-- Select Template --','class'=>'field selectarea')); ?>
  </div>
  <div class="row"> <?php echo $form->label($model,'job_title'); ?>
  					<?php echo $form->textField($model,'job_title',array('class'=>'input field')); ?> 
  </div>
  <div class="row"> <?php echo $form->label($model,'job_action'); ?> 
  					<?php echo $form->dropDownList($model,'job_action',array('no-action'=>'No Action','approved'=>'Approved','denied'=>'Denied','previewed'=>'Previewed'),array('empty'=>'-- Select Action --','class'=>'field selectarea')); ?> 
  </div>
  <div class="row"> <?php echo $form->label($model,'job_status'); ?> 
  					<?php echo $form->dropDownList($model,'job_status',array('archived'=>'Archived','created'=>'Created','mailed'=>'Mailed','processed'=>'Processed','queued'=>'Queued'),array('empty'=>'-- Select Status --','class'=>'field selectarea')); ?> 
  </div>
  
  <div class="row buttons">
    <div class="green btn-small"><?php echo CHtml::linkButton('Search', array('submit' => array('search'))); ?></div>
    <br />
    <br />
  </div>
  <?php $this->endWidget(); ?>
</div>
<!-- search-form -->
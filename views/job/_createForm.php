<?php
/* @var $this JobController */
/* @var $model Job */
/* @var $form CActiveForm */
?>

<div class="form">
  <?php 	
	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'job-form',
	'enableAjaxValidation'=>true,
	)); 
?>
  <div class="row"> <?php echo $form->label($formModel,'template_id'); ?> <?php echo $form->DropDownList($formModel,'template_id',CHtml::listData(UserTemplates::model()->findAll('template_states = "Published"'),'id','title'),array('empty'=>'-- Select Template --','class'=>'field selectarea')); ?> <?php echo $form->error($formModel,'template_id'); ?> </div>
  <div class="row"> <?php echo $form->label($formModel,'job_title'); ?> <?php echo $form->textField($formModel,'job_title',array('size'=>60,'maxlength'=>255,'class'=>'input field')); ?> <?php echo $form->error($formModel,'job_title'); ?> </div>
  
  <div class="row"> <?php echo $form->label($formModel,'job_type'); ?> <?php echo $form->DropDownList($formModel,'job_type',array('Mail'=>'Mail','Email'=>'Email'),array('empty'=>'-- Select Type --','class'=>'field selectarea')); ?> <?php echo $form->error($formModel,'job_type'); ?> </div>
   
  
   <div class="row"> <?php echo $form->label($formModel,'start_time'); ?>
    <?php $this->widget('ext.timepicker.timepicker', array(
    'model'=>$formModel,
    'name'=>'start_time',
	'class'=>'input field',
	'value'=>Yii::app()->params['dateNTime'],
	
));?>
    <?php echo $form->error($formModel,'start_time'); ?> </div>
    
  <div class="row"> <?php echo $form->label($formModel,'end_time'); ?>
    <?php $this->widget('ext.timepicker.timepicker', array(
    'model'=>$formModel,
    'name'=>'end_time',
	'class'=>'input field',
	'value'=>Yii::app()->params['dateNTime'],
	
));?>
    </div>
    
    <div class="row"> <?php echo $form->label($formModel,'color'); ?>
    <?php $this->widget('ext.SMiniColors.SActiveColorPicker', array(
 		  
		   'attribute'=>'color',
		   'model' => $formModel,
		   //'htmlOptions'=>array('class'=>'input field'),
		   )
		   );

      ?>
  </div>

  
  
  <div class="row"> <?php echo $form->label($formModel,'print_id'); ?> <?php echo $form->DropDownList($formModel,'print_id',CHtml::listData(Mailhousesetting::model()->findAll('status = 1'),'id','title'),array('empty'=>'-- Select Print --','class'=>'field selectarea')); ?> <?php echo $form->error($formModel,'print_id'); ?> </div>
  <div class="row buttons">
    <div class="green btn-small"><?php echo CHtml::linkButton($formModel->isNewRecord ? 'Create' : 'Save', array('submit' => array('index', 'id'=>$formModel->isNewRecord ? 0 : $formModel->id))); ?></div>
    <br />
    <br />
  </div>
  <?php $this->endWidget(); ?>
</div>
<!-- search-form -->
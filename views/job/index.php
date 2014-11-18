<?php
/* @var $this ImagelibraryController */
/* @var $model Imagelibrary */
$this->pageTitle = Yii::app()->name . ' Manage Job(s)';
$this->secondText = '';


// IF Errors 
if ($formModel->getErrors())
    Yii::app()->clientScript->registerScript("job-error", "jQuery('.create-form').css('display','block');", CClientScript::POS_LOAD);

//IF Called For Update
if ($formModel->id)
    Yii::app()->clientScript->registerScript("job-error", "jQuery('.create-form').css('display','block');", CClientScript::POS_LOAD);


if (Yii::app()->user->hasFlash('error')):
    echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
endif;

if (Yii::app()->user->hasFlash('warn')):
    echo '<div class="flash-notice">' . Yii::app()->user->getFlash('warn') . '</div>';
endif;

if (Yii::app()->user->hasFlash('success')):
    echo '<div class="flash-success">' . Yii::app()->user->getFlash('success') . '</div>';
endif;

// Registered Toggle Scripts
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	
	$('.create-form').hide();
	$('.search-form').toggle();
	
	return false;
});
$('.search-form form').submit(function(){
	$('#job-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

Yii::app()->clientScript->registerScript('create_job', "
$('.create-job').click(function(){
	
	$('.search-form').hide();
	$('.create-form').toggle();
	return false;
});
/*$('.create-form form').submit(function(){
	$('#job-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
*/

");
?>


<?php echo CHtml::link('Create Job', array('/imagelibrary/create'), array("class" => 'lnks create-job',)); ?>&nbsp;|&nbsp;<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button lnks')); ?>
<div class="search-form" style="display:none"> <br />
    <?php $this->renderPartial('_search', array('model' => $gridModel,)); ?>
</div>
<div class="create-form" style="display:none"> <br />
    <?php $this->renderPartial('_createForm', array('formModel' => $formModel,)); ?>
</div>


<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'job-grid',
    'dataProvider' => $gridModel->search(),
    'filter' => $gridModel,
    'columns' => array(
        array(
            'header' => 'Id',
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'raw',
            'htmlOptions' => array("width" => "10%"),
        ),
        array(
            'header' => 'Template',
            'name' => 'template_id',
            'filter' => Usertemplates::getTemplates(Yii::app()->user->id),
            'value' => 'CHtml::link($data->getTemplate()->title, array("usertemplates/view", "id"=>$data->template_id),array("class"=>"lnks"))',
            'type' => 'raw',
            'htmlOptions' => array("width" => "10%"),
        ),
        array(
            'header' => 'Title',
            'name' => 'job_title',
            'value' => 'CHtml::link($data->job_title, array("view", "id"=>$data->id),array("class"=>"lnks"))',
            'type' => 'raw',
            'htmlOptions' => array("width" => "20%"),
        ),
		
		 array(
            'header' => 'Type',
            'name' => 'job_type',
			'filter' => array('Mail'=>'Mail','Email'=>'Email'),
            'value' => 'ucfirst($data->job_type)',
            'type' => 'raw',
            'htmlOptions' => array("width" => "5%"),
        ),
		
        array(
            'header' => 'Action',
            'name' => 'job_action',
            'filter' => array('no-action' => 'No Action', 'approved' => 'Approved', 'denied' => 'Denied', 'previewed' => 'Previewed',),
            'value' => 'ucfirst($data->job_action)',
            'type' => 'raw',
            'htmlOptions' => array("width" => "8%"),
        ),
        array(
            'header' => 'Status',
            'name' => 'job_status',
            'filter' => array('archived' => 'Archived', 'created' => 'Created', 'mailed' => 'Mailed', 'processed' => 'Processed', 'queued' => 'Queued'),
            'value' => '($data->job_status != "archived" ? "<span style=\"color:green\">".ucfirst($data->job_status)."</span>": "<span style=\"color:red\">".ucfirst($data->job_status)."</span>")',
            'type' => 'html',
            'htmlOptions' => array("width" => "8%"),
        ),
		
		array(
            'header' => 'Color',
          	'value' => '"<span style=\"color:".$data->color."\">".strtoupper($data->color)."</span>"',
            'type' => 'html',
            'htmlOptions' => array("width" => "5%"),
        ),
		
		
       /* array(
            'header' => 'Bill Date',
            'visible' => '$data->type == "Mail"',
            'name' => 'billing_date',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array('model' => $gridModel, 'attribute' => 'billing_date', 'language' => 'en', 'options' => array('dateFormat' => 'mm-dd-yy')), true),
            'value' => 'date(Yii::app()->params["cGridTime"],strtotime($data->billing_date))',
            'type' => 'raw',
            'htmlOptions' => array("width" => "10%"),
        ),
        array(
            'header' => 'Billing',
            'visible' => '$data->type == "Mail"',
            'name' => 'billing_status',
            'filter' => array('unpaid' => 'Unpaid', 'paid' => 'Paid'),
            'value' => '($data->billing_status == "paid" ? "<span style=color:green>".ucfirst($data->billing_status)."</span>" : "<span style=color:red>".ucfirst($data->billing_status)."</span>")',
            'type' => 'raw',
            'htmlOptions' => array("width" => "10%"),
        ),*/
       /*
        *
        * Commented by Raja Rizwan
        * Dated: 14, 10 14
		*/
         array(

            'header' => 'Actions',
            'class' => 'CButtonColumn',
            'template' => '{update}{view}{approve}{deny}{payment}{mail}{email}{archive}{unarchive}',
            'htmlOptions' => array("width" => "20%"),
            'buttons' => array
                (
                // Update Option
                'update' => array('label' => 'Update',
                    'url' => 'Yii::app()->createUrl("/job/index", array("id"=>$data["id"]))',
                    'type' => 'html'
                ),
                // Preview Option
                'view' => array('label' => 'Preview as PDF',
                    'imageUrl' => Yii::app()->request->baseUrl . "/images/preview.png",
                    'visible' => '$data->job_status != "new"',
                    'options' => array("target" => "_blank", 'onclick' => "jQuery('#job-grid').addClass('grid-view-loading');setTimeout('location.reload();jQuery(\"\#job-grid\"\).removeClass(\"\grid-view-loading\"\)',10000);",),
                    'type' => 'html'
                ),
                // Approve Option
                'approve' => array(
                    'label' => 'Approve Job',
                    'visible' => '($data->job_action != "no-action")',
                    'imageUrl' => Yii::app()->request->baseUrl . "/images/approved.png",
                    'url' => 'Yii::app()->createUrl("/job/state", array("id"=>$data["id"],"opt"=>"approved"))',
                    'options' => array('ajax' => 
                                                array('type' => 'POST',
                                                      'url' => 'js:$(this).attr("href")',
                                                      'success' => 'js:function(data){ 
                                                                   	location.reload();
                                                                    js:$(".create-form").hide();
								    js:$(".search-form").hide();
						     }'),
                                       'style' => "margin:0 2px"),
                    'type' => 'raw'
                ),
                // Deny Option			
                'deny' => array(
                    'label' => 'Deny Job',
                    'visible' => '($data->job_action != "no-action")',
                    'imageUrl' => Yii::app()->request->baseUrl . "/images/deny.gif",
                    'url' => 'Yii::app()->createUrl("/job/state", array("id"=>$data["id"],"opt"=>"denied"))',
                    'options' => array('ajax' => 
                                                array('type' => 'POST',
                                                      'url' => 'js:$(this).attr("href")',
                                                      'success' => 'js:function(data){ 
                                                                    
                                                                    location.reload();
                                                                    js:$(".create-form").hide();
                                                                    js:$(".search-form").hide();
    						  }'),
                                        'style' => "margin:0 2px"),
                    'type' => 'raw'
                ),
                // Payment and Process Job Job		
                'payment' => array(
                    'label' => 'Job Payment',
                    'visible' => '($data->job_action == "approved" && $data->job_type == "Mail")',
                    'imageUrl' => Yii::app()->request->baseUrl . "/images/payment.png",
                    'url' => 'Yii::app()->createUrl("/job/payment", array("id"=>$data["id"]))',
                    'type' => 'raw'
                ),
				
				 'mail' => array(
                    'label' => 'Mail House',
                    'visible' => '($data->job_action == "approved" && $data->job_type == "Mail" && $data->userPackage == "Single" && $data->billing_status == "paid")',
                    'imageUrl' => Yii::app()->request->baseUrl . "/images/mail.png",
                    'url' => 'Yii::app()->createUrl("/job/mailhouse", array("id"=>$data["id"]))',
					'options' => array('style' => "margin:0 2px","target" => "_blank"),
                    'type' => 'raw'
                ),
				
				 'email' => array(
                    'label' => 'Email',
                    'visible' => '($data->job_action == "approved" && $data->job_type == "Email" && $data->userPackage == "Single")',
                    'imageUrl' => Yii::app()->request->baseUrl . "/images/email.png",
                    'url' => 'Yii::app()->createUrl("/job/email", array("id"=>$data["id"]))',
					'options' => array('style' => "margin:0 2px","target" => "_blank"),
                    'type' => 'raw'
                ),
			
               
                'archive' => array(
                    'label' => 'Archived',
                    'visible' => '$data->job_status != "archived"',
                    'imageUrl' => Yii::app()->request->baseUrl . "/images/archive.png",
                    'url' => 'Yii::app()->createUrl("/job/archive", array("id"=>$data["id"]))',
                    'options' => array('style' => "margin:0 2px"),
                    'type' => 'raw'
                ),
                'unarchive' => array(
                    'label' => 'Un archive',
                    'visible' => '$data->job_status == "archived"',
                    'imageUrl' => Yii::app()->request->baseUrl . "/images/unarchive.png",
                    'url' => 'Yii::app()->createUrl("/job/archive", array("id"=>$data["id"]))',
                    'options' => array('style' => "margin:0 2px"),
                    'type' => 'raw'
                ),
				
				
				
            )
        ),
    ),
   
));
?>

<script>
        function reloadGrid(data) {
            $.fn.yiiGridView.update('job-grid');
        }
</script>
<?php
    $this->pageTitle=Yii::app()->name . 'Job File';
?>
<?php

$form=$this->beginWidget('CActiveForm', array(
'id'=>'job-form',
'enableAjaxValidation'=>true,
));
?>
<div class="right"> <a href="#" class="lnks">Save & Exit</a> &nbsp;</div>
<div class="content"> 
            <div id="wizard">
                
                <h2>Select File</h2>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
                <div>Your file #</div>
            </div>     
</div>
<dir style="clear:both;"></dir>
<div style="margin-top: 20px;">
    <div class="green btn-medium left">
        <a href="/job/start_job_templates/">Back</a>
    </div>
    <div class="green btn-medium right">
        <a href="/job/start_job_starttime/">Next</a>
    </div>
</div>
<!-- search-form -->
 <?php $this->endWidget(); 
    Yii::app()->clientScript->registerScript('search', '
     $("input[type=\'text\']").focus(function () {
           var inputId = $(this).attr("id");
           $("#"+inputId+"_div").show(\'slow\');
        });
        $("input[type=\'text\']").focusout(function () {
           var inputId = $(this).attr("id");
           $("#"+inputId+"_div").hide(\'slow\');
        });
    ');
    
?>
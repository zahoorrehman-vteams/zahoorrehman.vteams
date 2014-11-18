<?php
    $this->pageTitle=Yii::app()->name . 'Job Start Time';
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
                
                <h2>Select Start Time</h2>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
                <div>&nbsp;</div>
            </div>     
</div>
<dir style="clear:both;"></dir>
<div style="margin-top: 20px;">
    <div class="green btn-medium left">
        <a href="/job/start_job_file/">Back</a>
    </div>
    <div class="green btn-medium right">
        <a href="/job/start_job_endtime/">Next</a>
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
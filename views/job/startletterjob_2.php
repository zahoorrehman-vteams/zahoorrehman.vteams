<?php
    $this->pageTitle=Yii::app()->name . 'Job Title';
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
                <section>
                    <div class="row">
                    <label class="required" for="Imagelibrary_title">
                    Title
                    <span class="required">*</span>
                    </label>
                    <input id="Imagelibrary_title" class="field input required" type="text" maxlength="150" name="Imagelibrary[title]">
                    <div id="Imagelibrary_title_div" style="display:none;">Internal use. Ex: "Job Test#1" <br> * mean required field</div>
                    </div>
                   
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
                </section>     
            </div>     
</div>
<div style="margin-top: 20px;">
    <div class="green btn-medium right">
        <a href="/job/start_job_templates/">Next</a>
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
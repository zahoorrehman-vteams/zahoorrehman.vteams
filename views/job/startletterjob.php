<?php
    $this->pageTitle=Yii::app()->name . 'Start Letter Job';
?>
<link rel="stylesheet" href="<?php echo Yii::app()->baseUrl;?>/thirdparty/css/jquery_step_by_step/normalize.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl;?>/thirdparty/css/jquery_step_by_step/main.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl;?>/thirdparty/css/jquery_step_by_step/jquery.steps.css">
        <script src="<?php echo Yii::app()->baseUrl;?>/thirdparty/js/jquery_step_by_step/modernizr-2.6.2.min.js"></script>
        <script src="<?php echo Yii::app()->baseUrl;?>/thirdparty/js/jquery_step_by_step/jquery-1.9.1.min.js"></script>
        <script src="<?php echo Yii::app()->baseUrl;?>/thirdparty/js/jquery_step_by_step/jquery.cookie-1.3.1.js"></script>
        <script src="<?php echo Yii::app()->baseUrl;?>/thirdparty/js/jquery_step_by_step/build/jquery.steps.js"></script>
<style type="text/css">
 .job_title{
        background: url(/images/input_icons/icon_titles.png) no-repeat left center #F2F4F5;
        padding-left: 20px;
        margin-left: 20px;
    }
.job_templates{

        background: url(/images/input_icons/x_office_document_template.png) no-repeat left center #F2F4F5;
        padding-left: 20px;
        margin-left: 20px;
}
</style>
          <?php
	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'job-form',
	'enableAjaxValidation'=>true,
	));
?>
        <div class="content">
            <script>
                $(function ()
                {
                    $("#wizard").steps({
                        headerTag: "h2",
                        bodyTag: "section",
                        transitionEffect: "slideLeft"
                    });
                });
            </script>
            <div id="wizard">
                <h2>JOB TITLE</h2>
                <section>
                    <div class="row">
                    <label class="required" for="Imagelibrary_title">
                    Title
                    <span class="required">*</span>
                    </label>
                    <input id="Imagelibrary_title" class="field input required" type="text" maxlength="150" name="Imagelibrary[title]">
                    </div>
                </section>

                <h2>TEMPLATE</h2>
                <section>
                   <div class="span4">
                         <div id="div_3" class="grid-template"><img alt="Nonprofit - Volunteer" src="/uploads/predefinedtemplate/3.png">
                           <div data-om-config="T2T Grid,Nonprofit - Volunteer" id="preview_hidden_div_3" class="template-detail track-om track-click fancybox tracked" style="display: none;"><a href="/Pretemplates/popup_show_detailed_template/3" class="btn">Preview</a></div>
                         </div>
                   </div>
                </section>

                <h2>FILE</h2>
                <section>
                    <div>
        <form method="post" action="/upload/Upload_file" id="upload-form" enctype="multipart/form-data">    <div class="row">
   <a class="lnks" href="/upload/download/990265c7">Download CSV Sample</a>&nbsp;|&nbsp;<a class="lnks" href="/upload/download/c17332ca">Download XLS (Ms-Execl) Sample</a>&nbsp;|&nbsp;<a class="lnks" href="/upload/download/ea3bec53">Download XML Sample</a>   </div>
  <div class="left"><h4><label for="Upload_title">Title</label></h4>
  <div class="toolTip tooltipster"></div>
  <div style="clear:both"></div>
    <input type="text" id="Upload_title" name="Upload[title]" class="input field" maxlength="255" size="60">
      </div>
  <div style="clear:both"></div>
  <div id="filefield" class="left">
   <h4><label for="Upload_file">File</label></h4>
  <div class="toolTip tooltipster"></div>
  </div>
  <div style="clear:both"></div>
  <div class="upload">
    <input type="hidden" name="Upload[file]" value="" id="ytUpload_file"><input type="file" id="Upload_file" name="Upload[file]" class="blue btn-medium" size="20">  </div>
        <p class="hint">Maximum File Size is allowed: 120 MB</p>
    <p class="hint">Only XML or CSV files are allowed.</p>
  <div style="clear:both"></div>
  <div class=" green btn-medium">
       <input type="submit" value="Upload" name="yt0" id="hidden_submit_button" style="display:none;">     
</div>
  </form>     <div style="display:none" id="loader"><img src="/themes/vostech/images/loading.gif"> File data is importing...</div>


<div id="load_grid"></div>
  <div id="section_2_div"></div>
  <div style="clear:both"></div>
</div>
                </section>
                <h2>START TIME</h2>
                <section>
                    <div class="row"> <?php echo $form->label($formModel,'start_time'); ?>
    <?php $this->widget('ext.timepicker.timepicker', array(
    'model'=>$formModel,
    'name'=>'start_time',
	'class'=>'input field',
	'value'=>Yii::app()->params['dateNTime'],

));?>
    <?php echo $form->error($formModel,'start_time'); ?> </div>
                </section>
                <h2>END TIME</h2>
                <section>
                     <div class="row"> <?php echo $form->label($formModel,'end_time'); ?>
    <?php $this->widget('ext.timepicker.timepicker', array(
    'model'=>$formModel,
    'name'=>'end_time',
	'class'=>'input field',
	'value'=>Yii::app()->params['dateNTime'],

));?>
    </div>
                </section>
                <h2>MAIL</h2>
                <section>
                    <div class="row"> Mail</div>
                    <textarea cols="70" rows="15"></textarea>
                    
                </section>
            </div>
     
        </div>
<!-- search-form -->
 <?php $this->endWidget(); ?>
<script>
$('.grid-template').mouseover(function(){
    var mydiv = $(this).attr('id');
    $('#preview_hidden_'+mydiv).show();
});
$('.grid-template').mouseout(function(){
    var mydiv = $(this).attr('id');
    $('#preview_hidden_'+mydiv).hide();
});
$('.email_template_class').click(function(){
    var my_link_caption = $(this).attr('my_caption');
    jQuery.ajax({
                'data':{'type': my_link_caption,'partial':'1'},
                'url':'/pretemplates/index',
                'cache':false,
                'success':function(output){
                        $('#container_matrix').html(output);
                }}).done(function(){
                        $('.grid-template').mouseover(function(){
                                var mydiv = $(this).attr('id');
                                $('#preview_hidden_'+mydiv).show();
                        });
                        $('.grid-template').mouseout(function(){
                                var mydiv = $(this).attr('id');
                                $('#preview_hidden_'+mydiv).hide();
                        });
                        $('.btn').fancybox({'width':'720px','autoDimensions':true,'titleShow':false,'modal':false,'type':'inline'});
                });
                return false;
});
$('#e_mail_template').click();


$("#form-3").steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }

                    // Forbid next action on "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age-2").val()) < 18)
                    {
                        return false;
                    }

                    // Needed in some cases if the user went back (clean up)
                    if (currentIndex < newIndex)
                    {
                        // To remove error styles
                        $("#form-3 .body:eq(" + newIndex + ") label.error").remove();
                        $("#form-3 .body:eq(" + newIndex + ") .error").removeClass("error");
                    }

                    $("#form-3").validate().settings.ignore = ":disabled,:hidden";
                    return $("#form-3").valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex)
                {
                    // Used to skip the "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
                    {
                        $("#form-3").steps("next");
                    }

                    // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3)
                    {
                        $("#form-3").steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex)
                {
                    $("#form-3").validate().settings.ignore = ":disabled";
                    return $("#form-3").valid();
                },
                onFinished: function (event, currentIndex)
                {
                    alert("Submitted!");
                }
            }).validate({
                errorPlacement: errorPlacement,
                rules: {
                    confirm: {
                        equalTo: "#password-2"
                    }
                }
            });
</script>
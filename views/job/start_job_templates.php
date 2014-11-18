<?php
    $this->pageTitle=Yii::app()->name . 'Job Template';
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
                
                    <div class="row">
             <div class="span4">
                 <div id="div_3" class="grid-template" ><img alt="Nonprofit - Volunteer" src="/uploads/predefinedtemplate/3.png">
                   <div data-om-config="T2T Grid,Nonprofit - Volunteer" id="preview_hidden_div_3" class="template-detail track-om track-click fancybox tracked" style="display: none;"><a href="#" class="btn">Preview</a></div>
                 </div>
             </div>
        </div>
                   
              
            </div>     
</div>
<dir style="clear:both;"></dir>
<div style="margin-top: 20px;">
    <div class="green btn-medium left">
        <a href="/job/startletterjob_2/">Back</a>
    </div>
    <div class="green btn-medium right">
        <a href="/job/start_job_file/">Next</a>
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
    Yii::app()->clientScript->registerScript('', "
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
$('#e_mail_template').click();");
?>
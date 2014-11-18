<?php
/* @var $this ImagelibraryController */
/* @var $model Imagelibrary */
$this->pageTitle = Yii::app()->name . ' Notification(s)';
$this->secondText = '';


if (Yii::app()->user->hasFlash('error')):
    echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
endif;

if (Yii::app()->user->hasFlash('warn')):
    echo '<div class="flash-notice">' . Yii::app()->user->getFlash('warn') . '</div>';
endif;

if (Yii::app()->user->hasFlash('success')):
    echo '<div class="flash-success">' . Yii::app()->user->getFlash('success') . '</div>';
endif;
?>
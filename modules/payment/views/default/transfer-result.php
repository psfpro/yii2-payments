<?php

use yii\helpers\Html;

/**
 * @var $success bool
 * @var $message string
 */
?>
<h1>Transfer</h1>
<?php if ($success) { ?>
<div class="alert alert-success" role="alert"> <strong>Well done!</strong> The operation completed successfully. </div>
<?php } else { ?>
<div class="alert alert-danger" role="alert"> <strong>Warning!</strong> <?= $message ?>. </div>
<?php } ?>
<?= Html::a('Return', ['index'], ['class' => 'btn btn-default']) ?>


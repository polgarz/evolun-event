<?php if (!empty($description)): ?>
    <?= $description ?>
<?php else: ?>
    <div class="text-muted"><p><?= Yii::t('event/description', 'No description') ?></p></div>
<?php endif ?>
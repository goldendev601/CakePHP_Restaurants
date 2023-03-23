<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="message error flashmessage-error" onclick="this.classList.add('hidden');"><i class="fa fa-close"></i><?= $message ?></div>

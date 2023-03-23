<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="message success flashmessage" onclick="this.classList.add('hidden')"> <?=$message ?></div>

<?php
    $id = $field['id'] ?? '';
    $name = $field['name'] ?? $id;
    $label = $field['label'] ?? '';
    $type = $field['type'] ?? 'text';
    $placeholder = $field['placeholder'] ?? '';
    $required = $field['required'] ?? false;
    $autocomplete = $field['autocomplete'] ?? null;
?>

<div class="form-group">
    <label for="<?= htmlspecialchars($id)?>">
        <?= htmlspecialchars($label) ?>
    </label>

    <input
        type="<?= htmlspecialchars($type) ?>"
        id="<?=htmlspecialchars($id)?>"
        name="<?=htmlspecialchars($name)?>"
        placeholder="<?=htmlspecialchars($placeholder)?>"
        <?=$required ? 'required' : '' ?>
        <?php if ($autocomplete !== null): ?>
            autocomplete="<?= htmlspecialchars($autocomplete) ?>"
        <?php endif; ?>
    >
</div>
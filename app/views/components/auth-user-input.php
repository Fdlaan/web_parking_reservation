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
    <label for="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">
        <?= htmlspecialchars($label) ?>
    </label>

    <?php if ($type === 'password'): ?>
        <div class="password-input-wrapper">
    <?php endif; ?>

        <input
            type="<?= htmlspecialchars($type) ?>"
            id="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>"
            name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
            placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8') ?>"
            data-auth-field="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>"
            <?=$required ? 'required' : '' ?>
            <?php if ($autocomplete !== null): ?>
                autocomplete="<?= htmlspecialchars($autocomplete, ENT_QUOTES, 'UTF-8') ?>"
            <?php endif; ?>
        >

    <?php if ($type === 'password'): ?>
            <button type="button" class="password-visibility-toggle" data-password-toggle="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" aria-label="Show <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>">
                <i class="fa-solid fa-eye" aria-hidden="true"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($id === 'password' && in_array($_GET['step'] ?? '', ['signup', 'reset-password'], true)): ?>
        <div class="password-strength" aria-live="polite">
            <div class="password-strength-meter" aria-hidden="true">
                <span></span><span></span><span></span>
            </div>
            <span class="password-strength-label">Use at least 8 characters.</span>
        </div>
    <?php endif; ?>
</div>
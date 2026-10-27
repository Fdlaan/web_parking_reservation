<?php
$fileTitle = 'Admin | Parkeasy';
$fileTabs = array_map(fn($item) => $item['title'], $entities);
staffHead($fileTitle);
staffBar('Parkeasy Admin', $fileTabs, $tab, '?page=admin&tab=', $admin['name']);
?>

<section class="staff-section">
    <span class="section-header"><?= $editRow ? 'Edit' : 'Add' ?> <?= e(strtolower($entity['title'])) ?></span>
    <form class="staff-form staff-panel" method="post" action="<?= e($back) ?>">
            <?= csrfField() ?>
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= (int) ($editRow['id'] ?? 0) ?>">

            <div class="staff-form-grid">
                <?php foreach ($entity['fields'] as $name => $field): ?>
                    <?php
                        $type = $field[1];
                        $value = $editRow[$name] ?? ($type === 'check' ? 1 : ($type === 'number' ? ($field[3] ?? '') : ''));
                        $required = !empty($field[2]) && !($type === 'password' && $editRow);
                    ?>
                    <div class="staff-field">
                        <?php if ($type === 'check'): ?>
                            <label class="staff-checkbox">
                                <input type="checkbox" name="<?= e($name) ?>" <?= $value ? 'checked' : '' ?>>
                                <?= e($field[0]) ?>
                            </label>
                        <?php elseif ($type === 'select'): ?>
                            <label>
                                <?= e($field[0]) ?>
                                <select name="<?= e($name) ?>" <?= $required ? 'required' : '' ?>>
                                    <option value="">-- choose --</option>
                                    <?php foreach ($options($field) as $key => $label): ?>
                                        <option value="<?= e($key) ?>" <?= (string) $key === (string) $value ? 'selected' : '' ?>>
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        <?php elseif ($type === 'password'): ?>
                            <label>
                                <?= e($field[0]) ?><?= $editRow ? ' - blank keeps current' : '' ?>
                                <input type="password" name="<?= e($name) ?>" autocomplete="new-password" <?= $required ? 'required' : '' ?>>
                            </label>
                        <?php else: ?>
                            <label>
                                <?= e($field[0]) ?>
                                <input
                                    type="<?= e($type) ?>"
                                    <?= $type === 'number' ? 'step="any"' : '' ?>
                                    name="<?= e($name) ?>"
                                    value="<?= e($value) ?>"
                                    <?= $required ? 'required' : '' ?>
                                >
                            </label>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="staff-form-actions">
                <button type="submit"><?= $editRow ? 'Update' : 'Create' ?></button>
                <?php if ($editRow): ?>
                    <a class="staff-button staff-button-secondary" href="<?= e($back) ?>">Cancel</a>
                <?php endif; ?>
            </div>
    </form>
</section>

<section class="staff-section">
    <span class="section-header"><?= e($entity['title']) ?></span>
    <?php
        staffTable($rows, function ($row) use ($back) {
            return '<div class="staff-row-actions"><a class="staff-button staff-button-secondary" href="'
                . e($back . '&edit=' . $row['id']) . '">Edit</a> '
                . '<form method="post" action="' . e($back)
                . '" data-confirm-message="Delete this row? This cannot be undone.">' . csrfField()
                . '<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="'
                . (int) $row['id'] . '"><button class="red">Delete</button></form></div>';
        });
    ?>
</section>

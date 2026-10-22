<?php

function parkEasyEscape($value): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function parkEasyFindMall(int $mallId, array $malls): ?array {
    foreach ($malls as $mall) {
        if ((int) $mall['id'] === $mallId) {
            return $mall;
        }
    }
    return null;
}

function parkEasyFindLocation(int $locationId, array $locations): ?array{
    foreach ($locations as $location) {
        if ((int) $location ['id'] === $locationId) {
            return $location;
        }
    }
    return null;
}

?>
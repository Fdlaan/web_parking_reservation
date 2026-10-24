<?php

function parkEasyCalculateDistance(
    float $userLat,
    float $userLng,
    float $mallLat,
    float $mallLng
): float {
    $earthRadius = 6371;

    $latDifference = deg2rad($mallLat - $userLat);
    $lngDifference = deg2rad($mallLng - $userLng);

    $a = sin($latDifference / 2) ** 2
        + cos(deg2rad($userLat))
        * cos(deg2rad($mallLat))
        * sin($lngDifference / 2) ** 2;

    $a = min(1, max(0, $a));

    return $earthRadius * 2 * atan2(
        sqrt($a),
        sqrt(1 - $a)
    );
}

?>
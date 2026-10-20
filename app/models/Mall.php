<?php

class Mall
{
    public static function getAll(): array
    {
        return require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/database/dummy_data/malls.php';
    }

    public static function getRecommended(
        float $userLat,
        float $userLng
    ): array {
        $malls = array_filter(
            self::getAll(),
            fn($mall) => !empty($mall['is_active'])
        );

        foreach ($malls as &$mall) {
            $mall['distance_km'] = self::calculateDistance(
                $userLat,
                $userLng,
                (float) $mall['latitude'],
                (float) $mall['longitude']
            );

            $mall['distance'] = number_format(
                $mall['distance_km'],
                1
            ) . ' KM';

            $mall['url'] = '?page=parking';
            $mall['alt'] = $mall['name'];
        }

        unset($mall);

        usort(
            $malls,
            fn($a, $b) => $a['distance_km'] <=> $b['distance_km']
        );

        return array_values($malls);
    }

    private static function calculateDistance(
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
}
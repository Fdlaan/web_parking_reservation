<?php

class Mall
{
    public static function getAll(): array
    {
        return require $_SERVER['DOCUMENT_ROOT'] . '/web_parking_reservation/database/dummy-data/parking-malls.php';
    }

    public static function getRecommended(
        float $userLat,
        float $userLng,
        array $parkingMalls
    ): array {
        $parkingMalls = array_values(array_filter(
            $parkingMalls,
            fn($parkingMall) => !empty($parkingMall['is_active'])
        ));

        foreach ($parkingMalls as &$parkingMall) {
            $parkingMall['distance_km'] = parkEasyCalculateDistance(
                $userLat,
                $userLng,
                (float) $parkingMall['latitude'],
                (float) $parkingMall['longitude']
            );

            $parkingMall['distance'] =
                number_format($parkingMall['distance_km'], 1) . ' KM';

            $parkingMall['url'] =
                '?page=detail&mall_id=' . (int) $parkingMall['id'];

            $parkingMall['alt'] = $parkingMall['name'];
        }

        unset($parkingMall);

        usort(
            $parkingMalls,
            fn($a, $b) => $a['distance_km'] <=> $b['distance_km']
        );

        return $parkingMalls;
    }
}

<?php

class Mall
{
    public static function getRecommended(
        float $userLat,
        float $userLng,
        array $parkingMalls,
        array $parkingRates = []
    ): array {
        $parkingMalls = array_values(array_filter(
            $parkingMalls,
            fn($mall) => !empty($mall['is_active'])
        ));

        foreach ($parkingMalls as &$mall) {
            $mall['distance_km'] = parkEasyCalculateDistance(
                $userLat,
                $userLng,
                (float) $mall['latitude'],
                (float) $mall['longitude']
            );

            $mall['distance'] = number_format($mall['distance_km'], 1) . ' KM';
            $mall['url'] = '?page=detail&mall_id=' . rawurlencode((string) $mall['mall_id']);
            $mall['alt'] = $mall['name'];
            $mall['prices'] = [
                'regular' => parkEasyFindRate((string) $mall['mall_id'], 'REGULER', $parkingRates)['price_per_slot'] ?? null,
                'valet' => parkEasyFindRate((string) $mall['mall_id'], 'VALET', $parkingRates)['price_per_slot'] ?? null,
            ];
        }

        unset($mall);

        usort(
            $parkingMalls,
            fn($a, $b) => $a['distance_km'] <=> $b['distance_km']
        );

        return $parkingMalls;
    }
}

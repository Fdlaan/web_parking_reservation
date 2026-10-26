<?php

/**
 * Loads the datasets the pages render. Shapes are what the controllers/views expect
 * (string ids, mall_id = parking_locations.id, city = region).
 * $reservations = every active (UNPAID/PAID) reservation, to know which slots are taken,
 * plus all reservations of the logged-in user, for Home and History.
 */
function parkEasyLoadDatabaseData(mysqli $conn, int $userId, string $assetUrl): array
{
    $image = fn(string $url) => preg_match('#^(https?:)?//|^/#', $url)
        ? $url
        : $assetUrl . '/images/' . ($url !== '' ? $url : 'icons/logo.svg');

    return [
        'userLocations' => array_map(fn($r) => [
            'id' => (int) $r['id'],
            'name' => $r['name'],
            'address' => $r['address'],
            'latitude' => (float) $r['latitude'],
            'longitude' => (float) $r['longitude'],
        ], staffRows($conn, 'SELECT id, name, address, latitude, longitude FROM user_locations ORDER BY id')),

        'parkingMalls' => array_map(fn($r) => [
            'mall_id' => (string) $r['id'],
            'name' => $r['name'],
            'address' => $r['address'],
            'city' => $r['region'],
            'latitude' => (float) $r['latitude'],
            'longitude' => (float) $r['longitude'],
            'image' => $image(trim((string) $r['image_url'])),
            'is_active' => (bool) $r['is_active'],
        ], staffRows($conn, 'SELECT id, name, address, region, latitude, longitude, image_url, is_active FROM parking_locations')),

        'parkingRates' => array_map(fn($r) => [
            'rate_id' => (string) $r['id'],
            'mall_id' => (string) $r['location_id'],
            'parking_type' => $r['parking_type'],
            'price_per_slot' => (int) $r['price_per_slot'],
            'admin_fee' => (int) $r['admin_fee'],
        ], staffRows($conn, 'SELECT id, location_id, parking_type, price_per_slot, admin_fee FROM parking_rates')),

        'parkingFloors' => array_map(fn($r) => [
            'floor_id' => (string) $r['id'],
            'mall_id' => (string) $r['location_id'],
            'floor_code' => $r['floor_code'],
            'floor_name' => $r['floor_code'],
            'sort_order' => (int) $r['sort_order'],
            'is_active' => true,
        ], staffRows($conn, 'SELECT id, location_id, floor_code, sort_order FROM floors')),

        'parkingSlots' => array_map(fn($r) => [
            'slot_id' => (string) $r['id'],
            'floor_id' => (string) $r['floor_id'],
            'slot_code' => $r['slot_code'],
            'parking_type' => $r['parking_type'],
            'is_active' => (bool) $r['is_active'],
        ], staffRows($conn, 'SELECT id, floor_id, slot_code, parking_type, is_active FROM parking_slots')),

        'reservations' => array_map(fn($r) => [
            'reservation_id' => (string) $r['id'],
            'user_id' => (int) $r['user_id'],
            'mall_id' => (string) $r['location_id'],
            'floor_id' => (string) $r['floor_id'],
            'slot_id' => (string) $r['slot_id'],
            'slot_code' => $r['slot_code'],
            'reserved_at' => $r['reserved_at'],
            'plate_number' => $r['plate_number'],
            'parking_type' => $r['parking_type'],
            'status' => $r['status'],
        ], staffRows($conn, "SELECT r.id, r.user_id, f.location_id, s.floor_id, r.slot_id, s.slot_code,
                                    r.reserved_at, r.plate_number, r.parking_type, r.status
                             FROM reservations r
                             JOIN parking_slots s ON s.id = r.slot_id
                             JOIN floors f ON f.id = s.floor_id
                             WHERE r.user_id = ? OR r.status IN ('UNPAID', 'PAID')", [$userId])),

        'vehicles' => staffRows($conn, 'SELECT id, plate_number FROM vehicles WHERE user_id = ? ORDER BY plate_number', [$userId]),
    ];
}

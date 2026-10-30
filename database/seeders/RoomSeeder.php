<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['room_number' => '101', 'room_type' => 'Consultation', 'is_available' => true],
            ['room_number' => '102', 'room_type' => 'Consultation', 'is_available' => true],
            ['room_number' => '103', 'room_type' => 'Treatment', 'is_available' => true],
            ['room_number' => '104', 'room_type' => 'Treatment', 'is_available' => true],
            ['room_number' => '105', 'room_type' => 'Procedure', 'is_available' => true],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(
                ['room_number' => $room['room_number']],
                $room
            );
        }
    }
}

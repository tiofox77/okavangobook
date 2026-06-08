<?php

namespace Database\Seeders\Angola;

use App\Models\Hotel;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Str;

trait HotelSeederTrait
{
    protected function createHotel(array $data, User $admin, Location $loc): void
    {
        $slug = Str::slug($data['name']);
        if (Hotel::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        Hotel::firstOrCreate(
            ['slug' => $slug],
            array_merge([
                'check_in_time'      => '14:00',
                'check_out_time'     => '12:00',
                'is_active'          => true,
                'is_featured'        => false,
                'accept_transfer'    => true,
                'accept_tpa_onsite'  => true,
                'website'            => null,
            ], $data, [
                'slug'        => $slug,
                'user_id'     => $admin->id,
                'location_id' => $loc->id,
            ])
        );
    }
}

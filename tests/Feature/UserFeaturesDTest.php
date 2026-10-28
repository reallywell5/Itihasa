<?php

namespace Tests\Feature;

use App\Models\Museum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFeaturesDTest extends TestCase
{
    use RefreshDatabase;

    public function test_museum_detail_displays_nearby_museums_in_same_city(): void
    {
        $museum1 = Museum::create([
            'name' => 'Museum Gedung Sate',
            'address' => 'Jl. Diponegoro, Bandung',
            'category' => 'museum',
            'description' => 'Gedung bersejarah',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
        ]);

        $museum2 = Museum::create([
            'name' => 'Museum Pos Indonesia',
            'address' => 'Jl. Cilaki, Bandung',
            'category' => 'museum',
            'description' => 'Museum pos dan perangko',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
        ]);

        $museum3 = Museum::create([
            'name' => 'Museum Nasional',
            'address' => 'Jakarta Pusat',
            'category' => 'museum',
            'description' => 'Museum nasional',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
        ]);

        $response = $this->get(route('museum.detail', $museum1->id));
        $response->assertOk();
        $response->assertSee('RUTE WISATA 1 HARI');
        $response->assertSee('Museum Terdekat');
        $response->assertSee('Museum Pos Indonesia');
        $response->assertSee('1.2 km');
    }
}

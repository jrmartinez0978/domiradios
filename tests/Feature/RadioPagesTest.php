<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RadioPagesTest extends TestCase
{
    /**
     * Test the homepage loads correctly.
     *
     * @return void
     */
    public function test_homepage_loads_successfully()
    {
        // The RadioController@index might try to fetch radios.
        // If it relies on specific data or the livewire component does,
        // this test might need factories or mocking later.
        // For now, just test the route and basic view name.
        $response = $this->get(route('emisoras.index'));

        $response->assertStatus(200);
        $response->assertViewIs('emisoras'); 
        // Since emisoras.blade.php uses <livewire:radio-index />,
        // asserting the view name 'emisoras' is a good start.
        // We could also assertSeeLivewire('radio-index') if needed.
    }
}

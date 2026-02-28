<?php

namespace Tests\Unit;

use App\Models\Radio;
use Illuminate\Foundation\Testing\RefreshDatabase; // Important for model tests
use Tests\TestCase;
use Illuminate\Support\Str;

class RadioModelTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database for each test

    /**
     * Test that a slug is generated automatically from the name when saving a Radio model.
     *
     * @return void
     */
    public function test_slug_is_generated_automatically_on_saving()
    {
        $radioName = "My Test Radio Station";
        $expectedSlug = Str::slug($radioName);

        // Create a new Radio model instance without a slug
        $radio = Radio::create([
            'name' => $radioName,
            // other required fields if any, assuming 'name' is sufficient for slug generation
            // and other fields can be null or have defaults.
            // Based on the model, other fields are fillable but not strictly required for this test.
        ]);

        // Assert that the slug was generated and matches the expected slug
        $this->assertEquals($expectedSlug, $radio->slug);
    }

    /**
     * Test that an existing slug is not overwritten if provided.
     *
     * @return void
     */
    public function test_existing_slug_is_not_overwritten()
    {
        $radioName = "Another Test Radio";
        $manualSlug = "custom-slug-provided";

        $radio = Radio::create([
            'name' => $radioName,
            'slug' => $manualSlug,
        ]);

        $this->assertEquals($manualSlug, $radio->slug);
        $this->assertNotEquals(Str::slug($radioName), $radio->slug);
    }
}

<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\UrbanLegend;
use Illuminate\Support\Str;

class UrbanLegendStoreTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = env('API_SECRET_KEY');
    }


    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title'       => 'Loira do Banheiro',
            'description' => 'Loira que assustava geral...',
            'latitude'    => -22.82,
            'longitude'   => -45.19,
            'country'     => 'BR',
            'city'        => 'Brasília',
        ], $overrides);
    }

    public function test_creates_an_urban_legend_and_returns_201(): void
    {
        User::factory()->create();

        $res = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/legend', $this->payload());

        $res->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'uuid', 'title', 'slug', 'description',
                    'latitude', 'longitude', 'country', 'city'
                ]
            ]);

        $this->assertDatabaseHas('urban_legends', [
            'title'   => 'Loira do Banheiro',
            'country' => 'BR',
            'city'    => 'Brasília',
        ]);

        $legend = UrbanLegend::first();
        $this->assertNotNull($legend->slug);
        $this->assertEquals(Str::slug('Loira do Banheiro'), $legend->slug);
        $this->assertEquals(Str::slug('Loira do Banheiro'), $legend->title_key);
    }

    public function test_ignores_slug_from_client_and_uses_model_generation(): void
    {
        User::factory()->create();

        $res = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/legend', $this->payload([
            'slug' => 'hacked-slug-should-not-be-used'
        ]));

        $res->assertCreated();

        $legend = UrbanLegend::first();
        $this->assertEquals(Str::slug('Loira do Banheiro'), $legend->slug);
        $this->assertNotEquals('hacked-slug-should-not-be-used', $legend->slug);
    }

    public function test_validates_required_fields_and_returns_422(): void
    {
        User::factory()->create();

        $res = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/legend', $this->payload([
            'title' => '', 
        ]));

        $res->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_rejects_duplicate_title_by_title_key_rule(): void
    {
        User::factory()->create();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/legend', $this->payload())->assertCreated();

        $res = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/legend', $this->payload());
        $res->assertStatus(422)->assertJsonValidationErrors(['title']);
    }

    
    public function test_updates_an_urban_legend_and_returns_200(): void
    {
        User::factory()->create();

        $createRes = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/legend', $this->payload());

        $createRes->assertCreated();

        $uuid = $createRes->json('data.uuid');

        $newTitle = 'Loira da lenda';

        $res = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/legend/{$uuid}", [
            'title' => $newTitle,
        ]);

        $res->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'uuid', 'title', 'slug', 'description',
                    'latitude', 'longitude', 'country', 'city'
                ]
            ])
            ->assertJsonPath('data.title', $newTitle)
            ->assertJsonPath('data.slug', Str::slug($newTitle));

        $this->assertDatabaseHas('urban_legends', [
            'uuid'      => $uuid,
            'title'     => $newTitle,
            'title_key' => Str::slug($newTitle),
            'slug'      => Str::slug($newTitle),
        ]);
    }




}

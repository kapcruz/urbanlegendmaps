<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Interfaces\UrbanLegendServiceInterface;
use App\Models\User;
use App\Models\UrbanLegend;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UrbanLegendServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UrbanLegendServiceInterface $service;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create();

        $this->service = $this->app->make(UrbanLegendServiceInterface::class);
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

    public function test_create_persists_legend_with_user_uuid_slug_and_title_key(): void
    {
        $data = $this->payload();

        $legend = $this->service->create($data);

        $this->assertInstanceOf(UrbanLegend::class, $legend);

        $this->assertDatabaseHas('urban_legends', [
            'uuid'    => $legend->uuid,
            'title' => 'Loira do Banheiro',
        ]);

        $legend = UrbanLegend::where('uuid', $legend->uuid)->first();
       
        $this->assertNotNull($legend->user_id);
        $this->assertEquals(User::first()->id, $legend->user_id);

        $this->assertNotNull($legend->uuid);

        $this->assertEquals(Str::slug('Loira do Banheiro'), $legend->slug);
        $this->assertEquals(Str::slug('Loira do Banheiro'), $legend->title_key);
    }

    public function test_list_returns_filtered_legends(): void
    {
        $this->service->create($this->payload([
            'title' => 'Lenda - Brasília',
            'city'  => 'Brasília',
        ]));

        $this->service->create($this->payload([
            'title' => 'Lenda - Florianópolis',
            'city'  => 'Florianópolis',
        ]));

        $results = $this->service->list(['city' => 'Brasília']);

        $this->assertCount(1, $results);
        $this->assertEquals('Brasília', $results->first()->city);
    }

    public function test_update_changes_title_and_regenerates_slug_and_title_key(): void
    {
        $original = $this->service->create($this->payload());

        $newTitle = 'Loira da lenda';

        $updated = $this->service->update($original->uuid, [
            'title' => $newTitle,
        ]);

        $this->assertEquals($newTitle, $updated->title);
        $this->assertEquals(Str::slug($newTitle), $updated->slug);
        $this->assertEquals(Str::slug($newTitle), $updated->title_key);

        $this->assertDatabaseHas('urban_legends', [
            'uuid'        => $original->uuid,
            'title'     => $newTitle,
            'title_key' => Str::slug($newTitle),
            'slug'      => Str::slug($newTitle),
        ]);
    }

    public function test_update_throws_model_not_found_for_invalid_uuid(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->service->update('invalid-uuid', [
            'title' => 'Anything',
        ]);
    }

    public function test_delete_soft_deletes_legend(): void
    {
        $legend = $this->service->create($this->payload());

        $result = $this->service->delete($legend->uuid);

        $this->assertTrue($result);

        $this->assertSoftDeleted('urban_legends', [
            'uuid' => $legend->uuid,
        ]);
    }


}

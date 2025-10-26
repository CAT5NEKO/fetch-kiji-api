<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchTitlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_latest_articles(): void
    {
        Article::factory()->count(50)->create();

        $response = $this->getJson('/api/fetch/titles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'url',
                        'title',
                        'published_at',
                    ],
                ],
            ])
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertCount(50, $response->json('data'));
    }

    public function test_fetches_maximum_100_articles(): void
    {
        Article::factory()->count(150)->create();

        $response = $this->getJson('/api/fetch/titles');

        $response->assertStatus(200);

        $this->assertCount(100, $response->json('data'));
    }

    public function test_articles_are_ordered_by_created_at_desc(): void
    {
        $article1 = Article::factory()->create(['created_at' => now()->subDays(3)]);
        $article2 = Article::factory()->create(['created_at' => now()->subDay()]);
        $article3 = Article::factory()->create(['created_at' => now()]);

        $response = $this->getJson('/api/fetch/titles');

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertEquals($article3->id, $data[0]['id']);
        $this->assertEquals($article2->id, $data[1]['id']);
        $this->assertEquals($article1->id, $data[2]['id']);
    }

    public function test_returns_empty_array_when_no_articles(): void
    {
        $response = $this->getJson('/api/fetch/titles');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [],
            ]);
    }

    public function test_does_not_require_authentication(): void
    {
        Article::factory()->count(5)->create();

        $response = $this->getJson('/api/fetch/titles');

        $response->assertStatus(200);
    }
}

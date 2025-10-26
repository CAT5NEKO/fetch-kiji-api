<?php

namespace Tests\Feature\Api\Admin;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_fetch_all_articles(): void
    {
        $user = User::factory()->create();
        Article::factory()->count(10)->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/admin/fetch-articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'url',
                        'title',
                        'source',
                        'published_at',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ])
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertCount(10, $response->json('data'));
    }

    public function test_articles_are_ordered_by_created_at_desc(): void
    {
        $user = User::factory()->create();

        $article1 = Article::factory()->create(['created_at' => now()->subDays(3)]);
        $article2 = Article::factory()->create(['created_at' => now()->subDay()]);
        $article3 = Article::factory()->create(['created_at' => now()]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/admin/fetch-articles');

        $response->assertStatus(200);

        $data = $response->json('data');

        // 最新順に並んでいることを確認
        $this->assertEquals($article3->id, $data[0]['id']);
        $this->assertEquals($article2->id, $data[1]['id']);
        $this->assertEquals($article1->id, $data[2]['id']);
    }

    public function test_returns_empty_array_when_no_articles(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/admin/fetch-articles');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [],
            ]);
    }

    public function test_requires_authentication(): void
    {
        Article::factory()->count(5)->create();

        $response = $this->getJson('/api/admin/fetch-articles');

        $response->assertStatus(401);
    }

    public function test_returns_all_fields_including_source(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create([
            'url' => 'https://example.com/test',
            'title' => 'Test Article',
            'source' => 'Test Blog',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/admin/fetch-articles');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'url' => 'https://example.com/test',
                'title' => 'Test Article',
                'source' => 'Test Blog',
            ]);
    }
}

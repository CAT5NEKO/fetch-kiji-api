<?php

namespace Tests\Feature\Api\Admin;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveNewTitleTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_save_new_article(): void
    {
        $user = User::factory()->create();

        $articleData = [
            'url' => 'https://example.com/article/12345',
            'title' => 'テスト記事タイトル',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/save-new-title', $articleData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => '記事を登録しました。',
                'data' => [
                    'url' => $articleData['url'],
                    'title' => $articleData['title'],
                ],
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'url',
                    'title',
                ],
            ]);

        $this->assertDatabaseHas('articles', [
            'url' => $articleData['url'],
            'title' => $articleData['title'],
        ]);
    }

    public function test_can_save_article_with_optional_fields(): void
    {
        $user = User::factory()->create();

        $articleData = [
            'url' => 'https://example.com/article/67890',
            'title' => 'オプションフィールド付き記事',
            'source' => 'Example Blog',
            'published_at' => '2025-10-25 09:00:00',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/save-new-title', $articleData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('articles', [
            'url' => $articleData['url'],
            'title' => $articleData['title'],
            'source' => $articleData['source'],
        ]);
    }

    public function test_requires_authentication(): void
    {
        $articleData = [
            'url' => 'https://example.com/article/12345',
            'title' => 'テスト記事',
        ];

        $response = $this->postJson('/api/admin/save-new-title', $articleData);

        $response->assertStatus(401);
    }

    public function test_url_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/save-new-title', [
                'title' => 'テスト記事',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }

    public function test_url_must_be_valid_url_format(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/save-new-title', [
                'url' => 'not-a-valid-url',
                'title' => 'テスト記事',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }

    public function test_url_must_be_unique(): void
    {
        $user = User::factory()->create();

        $existingArticle = Article::factory()->create([
            'url' => 'https://example.com/duplicate',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/save-new-title', [
                'url' => 'https://example.com/duplicate',
                'title' => '重複URL',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }

    public function test_url_must_not_exceed_512_characters(): void
    {
        $user = User::factory()->create();

        $longUrl = 'https://example.com/' . str_repeat('a', 500);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/save-new-title', [
                'url' => $longUrl,
                'title' => 'テスト記事',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }

    public function test_title_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/save-new-title', [
                'url' => 'https://example.com/article',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_title_must_not_exceed_255_characters(): void
    {
        $user = User::factory()->create();

        $longTitle = str_repeat('あ', 256);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/save-new-title', [
                'url' => 'https://example.com/article',
                'title' => $longTitle,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_published_at_must_be_valid_date(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/save-new-title', [
                'url' => 'https://example.com/article',
                'title' => 'テスト記事',
                'published_at' => 'invalid-date',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['published_at']);
    }
}

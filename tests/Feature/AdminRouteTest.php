<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminRouteTest extends TestCase
{
    use RefreshDatabase;

    // ===================== HELPER =====================

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create([
            'role' => $role,
            'email_verified_at' => now(),
        ]);
        return $user;
    }

    private function actingAsSuperAdmin(): User
    {
        $user = $this->createUserWithRole('super_admin');
        $this->actingAs($user);
        return $user;
    }

    private function actingAsAdmin(): User
    {
        $user = $this->createUserWithRole('admin');
        $this->actingAs($user);
        return $user;
    }

    private function actingAsOperator(): User
    {
        $user = $this->createUserWithRole('operator');
        $this->actingAs($user);
        return $user;
    }

    private function actingAsEditor(): User
    {
        $user = $this->createUserWithRole('editor');
        $this->actingAs($user);
        return $user;
    }

    // ===================== AUTHENTICATION TESTS =====================

    public function test_guest_is_redirected_to_login_when_accessing_admin()
    {
        $routes = [
            '/admin',
            '/admin/dashboard',
            '/admin/profile',
            '/admin/news',
            '/admin/news/create',
            '/admin/galleries',
            '/admin/galleries/create',
            '/admin/achievements',
            '/admin/achievements/create',
            '/admin/extracurriculars',
            '/admin/extracurriculars/create',
            '/admin/settings',
            '/admin/users',
            '/admin/users/create',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(302);
            $response->assertRedirectContains('login');
        }
    }

    // ===================== DASHBOARD TESTS =====================

    public function test_dashboard_redirects_to_dashboard_page()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin');
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_dashboard_page_can_be_rendered_for_super_admin()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_dashboard_page_can_be_rendered_for_admin()
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_dashboard_page_can_be_rendered_for_operator()
    {
        $this->actingAsOperator();
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_dashboard_page_can_be_rendered_for_editor()
    {
        $this->actingAsEditor();
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    // ===================== PROFILE TESTS =====================

    public function test_profile_page_can_be_rendered()
    {
        $user = $this->actingAsSuperAdmin();
        $response = $this->get('/admin/profile');
        $response->assertStatus(200);
    }

    public function test_profile_can_be_updated()
    {
        $user = $this->actingAsSuperAdmin();
        $response = $this->put('/admin/profile', [
            'name' => 'Updated Name',
            'email' => $user->email,
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertEquals('Updated Name', $user->fresh()->name);
    }

    public function test_profile_update_validates_required_fields()
    {
        $this->actingAsSuperAdmin();
        $response = $this->put('/admin/profile', [
            'name' => '',
            'email' => 'invalid-email',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_password_can_be_updated()
    {
        $user = $this->actingAsSuperAdmin();
        // First set a known password
        $user->password = bcrypt('current-password');
        $user->save();

        $response = $this->put('/admin/profile/password', [
            'current_password' => 'current-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }

    public function test_password_update_validates_current_password()
    {
        $user = $this->actingAsSuperAdmin();
        $user->password = bcrypt('current-password');
        $user->save();

        $response = $this->put('/admin/profile/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['current_password']);
    }

    // ===================== NEWS TESTS =====================

    public function test_news_index_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/news');
        $response->assertStatus(200);
    }

    public function test_news_create_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/news/create');
        $response->assertStatus(200);
    }

    public function test_news_can_be_stored()
    {
        $this->actingAsSuperAdmin();
        Storage::fake('public');

        $response = $this->post('/admin/news', [
            'title' => 'Test News Title',
            'content' => 'This is the content of the news article.',
            'category' => 'akademik',
            'is_published' => true,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.news.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('news', [
            'title' => 'Test News Title',
            'slug' => 'test-news-title',
            'category' => 'akademik',
        ]);
    }

    public function test_news_store_validates_required_fields()
    {
        $this->actingAsSuperAdmin();
        $response = $this->post('/admin/news', [
            'title' => '',
            'content' => '',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'content']);
    }

    public function test_news_edit_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $news = News::factory()->create();
        $response = $this->get("/admin/news/{$news->id}/edit");
        $response->assertStatus(200);
    }

    public function test_news_can_be_updated()
    {
        $this->actingAsSuperAdmin();
        $news = News::factory()->create(['title' => 'Original Title']);

        $response = $this->put("/admin/news/{$news->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'is_published' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertEquals('Updated Title', $news->fresh()->title);
    }

    public function test_news_can_be_deleted()
    {
        $this->actingAsSuperAdmin();
        $news = News::factory()->create();

        $response = $this->delete("/admin/news/{$news->id}");

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertSoftDeleted($news);
    }

    public function test_news_requires_authentication_for_post()
    {
        $response = $this->post('/admin/news', [
            'title' => 'Test',
            'content' => 'Content',
        ]);
        $response->assertStatus(302);
        $response->assertRedirectContains('login');
    }

    public function test_news_requires_authentication_for_put()
    {
        $news = News::factory()->create();
        $response = $this->put("/admin/news/{$news->id}", [
            'title' => 'Updated',
            'content' => 'Content',
        ]);
        $response->assertStatus(302);
        $response->assertRedirectContains('login');
    }

    public function test_news_requires_authentication_for_delete()
    {
        $news = News::factory()->create();
        $response = $this->delete("/admin/news/{$news->id}");
        $response->assertStatus(302);
        $response->assertRedirectContains('login');
    }

    // ===================== GALLERY TESTS =====================

    public function test_galleries_index_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/galleries');
        $response->assertStatus(200);
    }

    public function test_galleries_create_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/galleries/create');
        $response->assertStatus(200);
    }

    public function test_gallery_can_be_stored()
    {
        $this->actingAsSuperAdmin();
        Storage::fake('public');

        $file = UploadedFile::fake()->image('gallery.jpg');

        $response = $this->post('/admin/galleries', [
            'title' => 'Test Gallery',
            'images' => [$file],
            'description' => 'Gallery description',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.galleries.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('galleries', [
            'title' => 'Test Gallery',
        ]);
    }

    public function test_gallery_store_validates_required_fields()
    {
        $this->actingAsSuperAdmin();
        $response = $this->post('/admin/galleries', [
            'title' => '',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'images']);
    }

    public function test_gallery_edit_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $gallery = Gallery::factory()->create();
        $response = $this->get("/admin/galleries/{$gallery->id}/edit");
        $response->assertStatus(200);
    }

    public function test_gallery_can_be_updated()
    {
        $this->actingAsSuperAdmin();
        $gallery = Gallery::factory()->create(['title' => 'Original']);

        $response = $this->put("/admin/galleries/{$gallery->id}", [
            'title' => 'Updated Gallery',
            'description' => 'Updated description',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertEquals('Updated Gallery', $gallery->fresh()->title);
    }

    public function test_gallery_can_be_deleted()
    {
        $this->actingAsSuperAdmin();
        $gallery = Gallery::factory()->create();

        $response = $this->delete("/admin/galleries/{$gallery->id}");

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertModelMissing($gallery);
    }

    // ===================== ACHIEVEMENT TESTS =====================

    public function test_achievements_index_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/achievements');
        $response->assertStatus(200);
    }

    public function test_achievements_create_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/achievements/create');
        $response->assertStatus(200);
    }

    public function test_achievement_can_be_stored()
    {
        $this->actingAsSuperAdmin();

        $response = $this->post('/admin/achievements', [
            'title' => 'Juara 1 Lomba',
            'student_name' => 'Budi Santoso',
            'category' => 'akademik',
            'level' => 'provinsi',
            'description' => 'Deskripsi prestasi',
            'achievement_date' => '2025-01-15',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.achievements.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('achievements', [
            'title' => 'Juara 1 Lomba',
            'student_name' => 'Budi Santoso',
        ]);
    }

    public function test_achievement_store_validates_required_fields()
    {
        $this->actingAsSuperAdmin();
        $response = $this->post('/admin/achievements', [
            'title' => '',
            'student_name' => '',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'student_name']);
    }

    public function test_achievement_edit_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $achievement = Achievement::factory()->create();
        $response = $this->get("/admin/achievements/{$achievement->id}/edit");
        $response->assertStatus(200);
    }

    public function test_achievement_can_be_updated()
    {
        $this->actingAsSuperAdmin();
        $achievement = Achievement::factory()->create(['title' => 'Original']);

        $response = $this->put("/admin/achievements/{$achievement->id}", [
            'title' => 'Updated Achievement',
            'student_name' => 'Updated Student',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertEquals('Updated Achievement', $achievement->fresh()->title);
    }

    public function test_achievement_can_be_deleted()
    {
        $this->actingAsSuperAdmin();
        $achievement = Achievement::factory()->create();

        $response = $this->delete("/admin/achievements/{$achievement->id}");

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertModelMissing($achievement);
    }

    // ===================== EXTRACURRICULAR TESTS =====================

    public function test_extracurriculars_index_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/extracurriculars');
        $response->assertStatus(200);
    }

    public function test_extracurriculars_create_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/extracurriculars/create');
        $response->assertStatus(200);
    }

    public function test_extracurricular_can_be_stored()
    {
        $this->actingAsSuperAdmin();

        $response = $this->post('/admin/extracurriculars', [
            'name' => 'Paskibra',
            'description' => 'Pasukan pengibar bendera',
            'coach' => 'Pak Guru',
            'schedule' => 'Setiap Sabtu',
            'category' => 'olahraga',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.extracurriculars.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('extracurriculars', [
            'name' => 'Paskibra',
            'slug' => 'paskibra',
        ]);
    }

    public function test_extracurricular_store_validates_required_fields()
    {
        $this->actingAsSuperAdmin();
        $response = $this->post('/admin/extracurriculars', [
            'name' => '',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_extracurricular_edit_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $extracurricular = Extracurricular::factory()->create();
        $response = $this->get("/admin/extracurriculars/{$extracurricular->id}/edit");
        $response->assertStatus(200);
    }

    public function test_extracurricular_can_be_updated()
    {
        $this->actingAsSuperAdmin();
        $extracurricular = Extracurricular::factory()->create(['name' => 'Original']);

        $response = $this->put("/admin/extracurriculars/{$extracurricular->id}", [
            'name' => 'Updated Ekskul',
            'description' => 'Updated description',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertEquals('Updated Ekskul', $extracurricular->fresh()->name);
    }

    public function test_extracurricular_can_be_deleted()
    {
        $this->actingAsSuperAdmin();
        $extracurricular = Extracurricular::factory()->create();

        $response = $this->delete("/admin/extracurriculars/{$extracurricular->id}");

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertModelMissing($extracurricular);
    }

    // ===================== SETTINGS TESTS =====================

    public function test_settings_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/settings');
        $response->assertStatus(200);
    }

    public function test_settings_can_be_updated()
    {
        $this->actingAsSuperAdmin();

        $response = $this->put('/admin/settings', [
            'site_name' => 'SMAN 1 Barsel',
            'site_description' => 'Description of school',
            'address' => 'Jl. Pendidikan No.1',
            'phone' => '08123456789',
            'email' => 'school@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'key' => 'site_name',
            'value' => 'SMAN 1 Barsel',
        ]);
    }

    // ===================== USER TESTS =====================

    public function test_users_index_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/users');
        $response->assertStatus(200);
    }

    public function test_users_create_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/users/create');
        $response->assertStatus(200);
    }

    public function test_user_can_be_stored()
    {
        $this->actingAsSuperAdmin();

        $response = $this->post('/admin/users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_user_store_validates_required_fields()
    {
        $this->actingAsSuperAdmin();
        $response = $this->post('/admin/users', [
            'name' => '',
            'email' => '',
            'password' => '',
            'role' => '',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_user_edit_page_can_be_rendered()
    {
        $this->actingAsSuperAdmin();
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->get("/admin/users/{$user->id}/edit");
        $response->assertStatus(200);
    }

    public function test_user_can_be_updated()
    {
        $this->actingAsSuperAdmin();
        $user = User::factory()->create(['name' => 'Original Name', 'role' => 'admin']);

        $response = $this->put("/admin/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => 'operator',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertEquals('Updated Name', $user->fresh()->name);
        $this->assertEquals('operator', $user->fresh()->role);
    }

    public function test_user_can_be_deleted()
    {
        $this->actingAsSuperAdmin();
        $user = User::factory()->create(['role' => 'editor']);

        $response = $this->delete("/admin/users/{$user->id}");

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertModelMissing($user);
    }

    public function test_super_admin_cannot_delete_self()
    {
        $superAdmin = $this->actingAsSuperAdmin();

        $response = $this->delete("/admin/users/{$superAdmin->id}");

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertModelExists($superAdmin);
    }

    // ===================== RBAC TESTS =====================

    // --- News: allowed roles: super_admin, admin, operator, editor ---
    public function test_news_page_allowed_for_admin()
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/news');
        $response->assertStatus(200);
    }

    public function test_news_page_allowed_for_operator()
    {
        $this->actingAsOperator();
        $response = $this->get('/admin/news');
        $response->assertStatus(200);
    }

    public function test_news_page_allowed_for_editor()
    {
        $this->actingAsEditor();
        $response = $this->get('/admin/news');
        $response->assertStatus(200);
    }

    // --- Galleries: allowed roles: super_admin, admin ---
    public function test_galleries_page_forbidden_for_operator()
    {
        $this->actingAsOperator();
        $response = $this->get('/admin/galleries');
        $response->assertStatus(403);
    }

    public function test_galleries_page_forbidden_for_editor()
    {
        $this->actingAsEditor();
        $response = $this->get('/admin/galleries');
        $response->assertStatus(403);
    }

    public function test_galleries_page_allowed_for_admin()
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/galleries');
        $response->assertStatus(200);
    }

    // --- Achievements: allowed roles: super_admin, admin, editor ---
    public function test_achievements_page_forbidden_for_operator()
    {
        $this->actingAsOperator();
        $response = $this->get('/admin/achievements');
        $response->assertStatus(403);
    }

    public function test_achievements_page_allowed_for_editor()
    {
        $this->actingAsEditor();
        $response = $this->get('/admin/achievements');
        $response->assertStatus(200);
    }

    public function test_achievements_page_allowed_for_admin()
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/achievements');
        $response->assertStatus(200);
    }

    // --- Extracurriculars: allowed roles: super_admin, admin ---
    public function test_extracurriculars_page_forbidden_for_operator()
    {
        $this->actingAsOperator();
        $response = $this->get('/admin/extracurriculars');
        $response->assertStatus(403);
    }

    public function test_extracurriculars_page_forbidden_for_editor()
    {
        $this->actingAsEditor();
        $response = $this->get('/admin/extracurriculars');
        $response->assertStatus(403);
    }

    public function test_extracurriculars_page_allowed_for_admin()
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/extracurriculars');
        $response->assertStatus(200);
    }

    // --- Settings: allowed roles: super_admin, admin ---
    public function test_settings_page_forbidden_for_operator()
    {
        $this->actingAsOperator();
        $response = $this->get('/admin/settings');
        $response->assertStatus(403);
    }

    public function test_settings_page_forbidden_for_editor()
    {
        $this->actingAsEditor();
        $response = $this->get('/admin/settings');
        $response->assertStatus(403);
    }

    public function test_settings_page_allowed_for_admin()
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/settings');
        $response->assertStatus(200);
    }

    // --- Users: allowed roles: super_admin only ---
    public function test_users_page_forbidden_for_admin()
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_users_page_forbidden_for_operator()
    {
        $this->actingAsOperator();
        $response = $this->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_users_page_forbidden_for_editor()
    {
        $this->actingAsEditor();
        $response = $this->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_users_page_allowed_for_super_admin()
    {
        $this->actingAsSuperAdmin();
        $response = $this->get('/admin/users');
        $response->assertStatus(200);
    }

    // ===================== METHOD NOT ALLOWED TESTS =====================

    public function test_no_method_not_allowed_on_admin_routes()
    {
        $this->actingAsSuperAdmin();

        // Test that GET routes don't return 405
        $getRoutes = [
            '/admin/dashboard',
            '/admin/profile',
            '/admin/news',
            '/admin/news/create',
            '/admin/galleries',
            '/admin/galleries/create',
            '/admin/achievements',
            '/admin/achievements/create',
            '/admin/extracurriculars',
            '/admin/extracurriculars/create',
            '/admin/settings',
            '/admin/users',
            '/admin/users/create',
        ];

        foreach ($getRoutes as $route) {
            $response = $this->get($route);
            $this->assertNotEquals(405, $response->getStatusCode(), "Route $route returned 405 Method Not Allowed");
            $this->assertNotEquals(404, $response->getStatusCode(), "Route $route returned 404 Not Found");
        }

        // Test POST routes (just check they don't return 404/405 when accessed)
        $postRoutes = [
            '/admin/news',
            '/admin/galleries',
            '/admin/achievements',
            '/admin/extracurriculars',
            '/admin/users',
        ];

        foreach ($postRoutes as $route) {
            $response = $this->post($route, ['_token' => csrf_token()]);
            // We expect 302 (validation errors) not 404 or 405
            $this->assertNotEquals(404, $response->getStatusCode(), "Route $route returned 404 Not Found");
            $this->assertNotEquals(405, $response->getStatusCode(), "Route $route returned 405 Method Not Allowed");
        }
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//!!! not other

class CategoriesControllerTest extends TestCase
{
    use RefreshDatabase;

    // тестовая база laravel_testing и env.testing, иначе сотрет основную

    public function setUp(): void
    {
        // first include all the normal setUp operations
        parent::setUp();

        // now re-register all the roles and permissions (clears cache and reloads relations)
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }

    protected function afterRefreshingDatabase()
    {
        $this->seed(); //run database/seeders/DatabaseSeeder.php
    }
// =============================================================================================================
    public function test_admin_categories_index_200()
    {
        $response = $this->actingAs($this->getUser())->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    public function test_customer_categories_index_403()
    {
        $response = $this->actingAs($this->getUser('customer'))->get(route('admin.categories.index'));
        $response->assertStatus(403);
    }

    public function test_see_admin_admin_categories_create_200()
    {
        $response = $this->actingAs($this->getUser('admin'))->get(route('admin.categories.create'));
        $response->assertStatus(200);
    }

    public function test_see_customer_categories_create_403()
    {
        $response = $this->actingAs($this->getUser('customer'))->get(route('admin.categories.create'));
        $response->assertStatus(403);
    }

// =================================================================

    public function test_admin_allow_see_categories_200()
    {
        $categories = Category::orderByDesc('id')->paginate(10)->pluck('name')->toArray();
        $response = $this->actingAs($this->getUser())->get(route('admin.categories.index'));

//        dd(Category::orderByDesc('id')->paginate(10));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
        $response->assertSeeInOrder($categories);
    }

    public function test_customer_is_not_allow_to_see_categories_403()
    {
        $response = $this->actingAs($this->getUser('customer'))->get(route('admin.categories.index'));
        $response->assertStatus(403);
    }

    public function test_create_category_with_valid_data()
    {
        $data = array_merge(
            Category::factory()->make()->toArray(),
            ['parent_id' => Category::all()->random()?->id]
        );

        $response = $this->actingAs($this->getUser())
            ->post(
                route('admin.categories.store'),
                $data
            );

        $response->assertStatus(302);
        $response->assertRedirectToRoute('admin.categories.index');
        $this->assertDatabaseHas('categories', [
            'name' => $data['name']
        ]);
    }

    public function test_category_with_invalid_data()
    {
        $data = ['name' => 'A'];

        $response = $this->actingAs($this->getUser())
            ->post(
                route('admin.categories.store'),
                $data
            );
        $response->assertStatus(302);
//        $response->assertSee('The name must be at least 2 characters.');
//        $response->assertInvalid([
//            'name' => 'The name must be at least 2 characters.'
//        ]);

    }

    public function test_see_admin_dashboard_200()
    {
        $response = $this->actingAs($this->getUser('admin'))->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }
    public function test_see_customer_dashboard_403()
    {
        $response = $this->actingAs($this->getUser('customer'))->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_database_users_email_admin_admin_com_200()
    {
        $this->assertDatabaseHas('users', [
            'email' => 'admin@admin.com'
        ]);
    }

    public function test_invalid_path_categories_404()
    {
        $response = $this->get('/categories_err_path');
        $response->assertStatus(404);
    }

    public function getUser(string $role = 'admin')
    {
        return User::role($role)->first();
    }

}

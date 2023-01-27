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

    // тестовая база laravel_testing и env.testing иначе сотрет основную

    public function setUp(): void
    {
        // first include all the normal setUp operations
        parent::setUp();

        // now re-register all the roles and permissions (clears cache and reloads relations)
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }

    protected function afterRefreshingDatabase()
    {
        $this->seed();
    }
// -------------------------------------------------------------------------------------
//    public function test_admin_allow_see_categories()
//    {
//        $user = User::role('admin')->first();
//        $categories = Category::orderByDesc('id')->paginate(5)->pluck('name')->toArray();
//        $response = $this->actingAs($user)->get(route('admin.categories.index'));
//
////        dd(Category::orderByDesc('id')->paginate(10));
//
//        $response->assertStatus(200);
//        $response->assertViewIs('admin.categories.index');
//        $response->assertSeeInOrder($categories);
//    }
//
//    public function test_customer_not_allow_see_categories()
//    {
//        $user = User::role('customer')->first();
//        $response = $this->actingAs($user)->get(route('admin.categories.index'));
//
////        $response->assertStatus(200); //тест выдаст ошибку т.к. ожидаем 403
//        $response->assertStatus(403); //тест пройдет
//    }

// =================================================================

    public function test_admin_allow_see_categories()
    {
        $categories = Category::orderByDesc('id')->paginate(5)->pluck('name')->toArray();
        $response = $this->actingAs($this->getUser())->get(route('admin.categories.index'));

//        dd(Category::orderByDesc('id')->paginate(10));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
        $response->assertSeeInOrder($categories);
    }

    public function test_customer_not_allow_see_categories()
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
        $data = ['name'=> 'A'];

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

    public function getUser(string $role = 'admin')
    {
        return User::role($role)->first();

    }
}

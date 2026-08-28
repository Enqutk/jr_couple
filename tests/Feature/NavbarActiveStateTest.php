<?php

namespace Tests\Feature;

use App\Models\Organization;
use Database\Seeders\NavbarMenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavbarActiveStateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Organization::query()->create([
            'title' => 'JR Test',
            'status' => 'active',
        ]);

        $this->seed(NavbarMenuSeeder::class);
    }

    public function test_only_home_is_active_on_the_homepage(): void
    {
        $this->assertActiveNavLabel($this->get('/'), 'Home');
    }

    public function test_only_store_is_active_on_the_store_page(): void
    {
        $this->assertActiveNavLabel($this->get('/store'), 'Store');
    }

    public function test_only_blog_is_active_on_the_blog_page(): void
    {
        $this->assertActiveNavLabel($this->get('/blog'), 'Blog');
    }

    public function test_only_contact_is_active_on_the_contact_page(): void
    {
        $this->assertActiveNavLabel($this->get('/contact'), 'Contact');
    }

    private function assertActiveNavLabel($response, string $expected): void
    {
        $response->assertOk();

        preg_match_all(
            '/class="([^"]*nav-link[^"]*)"[^>]*>\s*([^<]+)/',
            $response->getContent(),
            $matches,
            PREG_SET_ORDER
        );

        $active = [];
        foreach ($matches as $match) {
            if (str_contains($match[1], 'active')) {
                $active[] = trim($match[2]);
            }
        }

        $this->assertSame([$expected], $active, 'Active nav items: '.implode(', ', $active));
    }
}

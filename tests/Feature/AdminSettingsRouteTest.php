<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminSettingsRouteTest extends TestCase
{
    public function test_admin_settings_component_and_route_are_available(): void
    {
        $this->assertTrue(class_exists(\App\Http\Livewire\Admin\AdminProfileSettings::class));

        $route = $this->app['router']->getRoutes()->getByName('admin.settings');

        $this->assertNotNull($route);
        $this->assertSame(
            \App\Http\Livewire\Admin\AdminProfileSettings::class . '@__invoke',
            $route->getAction('uses')
        );
    }
}

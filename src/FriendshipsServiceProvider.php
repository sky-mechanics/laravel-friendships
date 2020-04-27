<?php

namespace Demency\Friendships;

use Illuminate\Support\ServiceProvider;

class FriendshipsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->publishes([
            '0000_00_00_000000_create_friendships_table.stub.php'        => database_path('migrations') . date('Y_m_d_His', time()) . '_create_friendships_table.php',
            '0000_00_00_000000_create_friendships_groups_table.stub.php' => database_path('migrations') . date('Y_m_d_His', time() + 1) . '_create_friendships_groups_table.php'
        ], 'migrations');
        $this->publishes([__DIR__ . '/../config/friendships.php' => config_path('friendships.php')], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}

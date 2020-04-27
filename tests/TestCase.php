<?php

namespace Tests;

use Demency\Friendships\FriendshipsServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $userOne;
    protected $userTwo;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $userOne = User::forceCreate([
            'name' => 'omatamix',
            'email' => 'omatamix@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        $userTwo = User::forceCreate([
            'name' => 'demency',
            'email' => 'example@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            FriendshipsServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('friendships.tables.fr_groups_pivot', 'user_friendship_groups');
        $app['config']->set('friendships.tables.fr_pivot', 'friendships');
        $app['config']->set('friendships.groups.acquaintances', 0);
        $app['config']->set('friendships.groups.close_friends', 1);
        $app['config']->set('friendships.groups.family', 2);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
}

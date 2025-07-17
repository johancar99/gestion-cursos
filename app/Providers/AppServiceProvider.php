<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar implementaciones de repositorios
        $this->app->bind(\App\Domain\User\Repositories\UserRepository::class, \App\Infrastructure\Persistence\Eloquent\UserRepository::class);
        $this->app->bind(\App\Domain\Authentication\Repositories\AuthenticationTokenRepository::class, \App\Infrastructure\Persistence\Eloquent\AuthenticationTokenRepository::class);
        $this->app->bind(\App\Domain\Course\Repositories\CourseRepository::class, \App\Infrastructure\Persistence\Eloquent\CourseRepository::class);
        $this->app->bind(\App\Domain\Student\Repositories\StudentRepository::class, \App\Infrastructure\Persistence\Eloquent\StudentRepository::class);
        $this->app->bind(\App\Domain\Enrollment\Repositories\EnrollmentRepository::class, \App\Infrastructure\Persistence\Eloquent\EnrollmentRepository::class);
        
        // Registrar implementaciones de servicios de dominio
        $this->app->bind(\App\Domain\User\Services\PasswordHasher::class, \App\Infrastructure\Services\PasswordHasher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Book;
use App\Policies\AuthorPolicy;
use App\Policies\BookPolicy;
use App\Repositories\AuthorRepository;
use App\Repositories\BookRepository;
use App\Repositories\Interfaces\AuthorRepository as AuthorRepositoryContract;
use App\Repositories\Interfaces\BookRepository as BookRepositoryContract;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthorRepositoryContract::class, AuthorRepository::class);
        $this->app->bind(BookRepositoryContract::class, BookRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Author::class, AuthorPolicy::class);
        Gate::policy(Book::class, BookPolicy::class);
    }
}

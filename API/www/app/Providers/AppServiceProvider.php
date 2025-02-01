<?php

namespace App\Providers;

use App\Contract\UploadFileContract;
use App\Contract\UploadFileItemContract;
use App\Contract\UploadFileStatusContract;
use App\Repository\UploadFileItemRepository;
use App\Repository\UploadFileRepository;
use App\Repository\UploadFileStatusRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UploadFileContract::class, UploadFileRepository::class);
        $this->app->bind(UploadFileItemContract::class, UploadFileItemRepository::class);
        $this->app->bind(UploadFileStatusContract::class, UploadFileStatusRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

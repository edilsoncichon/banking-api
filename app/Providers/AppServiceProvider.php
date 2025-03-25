<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Conta\Repository\CreateAccountRepository;
use App\Domain\Conta\Repository\FindByAccountNumberRepository;
use App\Infra\Database\Eloquent\Repositories;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FindByAccountNumberRepository::class, Repositories\FindByAccountNumberRepository::class);
        $this->app->bind(CreateAccountRepository::class, Repositories\CreateAccountRepository::class);
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}

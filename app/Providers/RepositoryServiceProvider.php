<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\TambakRepositoryInterface;
use App\Repositories\Eloquent\TambakRepository;
use App\Repositories\Contracts\DataTrainRepositoryInterface;
use App\Repositories\Eloquent\DataTrainRepository;
use App\Repositories\Contracts\HasilNaiveRepositoryInterface;
use App\Repositories\Eloquent\HasilNaiveRepository;
use App\Repositories\Contracts\PrediksiRepositoryInterface;
use App\Repositories\Eloquent\PrediksiRepository;
use App\Repositories\Contracts\KudRepositoryInterface;
use App\Repositories\Eloquent\KudRepository;
use App\Repositories\Contracts\TimbanganRepositoryInterface;
use App\Repositories\Eloquent\TimbanganRepository;
use App\Repositories\Contracts\IkanRepositoryInterface;
use App\Repositories\Eloquent\IkanRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TambakRepositoryInterface::class, TambakRepository::class);
        $this->app->bind(DataTrainRepositoryInterface::class, DataTrainRepository::class);
        $this->app->bind(HasilNaiveRepositoryInterface::class, HasilNaiveRepository::class);
        $this->app->bind(PrediksiRepositoryInterface::class, PrediksiRepository::class);
        $this->app->bind(KudRepositoryInterface::class, KudRepository::class);
        $this->app->bind(TimbanganRepositoryInterface::class, TimbanganRepository::class);
        $this->app->bind(IkanRepositoryInterface::class, IkanRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobFailed;
use App\Scopes\UserScope;
use App\JobStatus;
use App\Jobs\ParsePrice;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            if ($event->job->getQueue() === 'price_list') {
                $payload = $event->job->payload();
                $job = unserialize($payload['data']['command']);

                JobStatus::withoutGlobalScope(UserScope::class)->updateOrCreate(
                    [
                        'user_id' => $job->getUserId(),
                        'contractor_id' => $job->getContractorId()
                    ],
                    [
                        'status_id' => 2,
                        'message' => 'Прайс в процессе обработки',
                    ]
                );
            }

        });

        Queue::after(function (JobProcessed $event) {
            if ($event->job->getQueue() === 'price_list') {
                $payload = $event->job->payload();
                $job = unserialize($payload['data']['command']);

                JobStatus::withoutGlobalScope(UserScope::class)->where([
                    'user_id' => $job->getUserId(),
                    'contractor_id' => $job->getContractorId()
                ])->delete();
            }
        });

        Queue::failing(function (JobFailed $event) {
            if ($event->job->getQueue() === 'price_list') {
                $payload = $event->job->payload();
                $job = unserialize($payload['data']['command']);

                JobStatus::withoutGlobalScope(UserScope::class)->updateOrCreate(
                    [
                        'user_id' => $job->getUserId(),
                        'contractor_id' => $job->getContractorId()
                    ],
                    [
                        'status_id' => 3,
                        'message' => mb_substr($event->exception->getMessage(), 0, 1024),
                    ]
                );
            }
        });

    }
}

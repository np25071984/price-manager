<?php

namespace App\Providers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobFailed;
use App\JobStatus;

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
            $payload = $event->job->payload();
            $job = unserialize($payload['data']['command']);

            JobStatus::updateOrCreate(
                ['contractor_id' => $job->getContractorId()],
                [
                    'status_id' => 2,
                    'message' => 'Прайс в процессе обработки',
                ]
            );
        });

        Queue::after(function (JobProcessed $event) {
            $payload = $event->job->payload();
            $job = unserialize($payload['data']['command']);

            JobStatus::where(['contractor_id' => $job->getContractorId()])->delete();
        });

        Queue::failing(function (JobFailed $event) {
            $payload = $event->job->payload();
            $job = unserialize($payload['data']['command']);

            JobStatus::updateOrCreate(
                ['contractor_id' => null],
                [
                    'status_id' => 3,
                    'message' => 'При обработке прайса произошла ошибка!',
                ]
            );
        });

        Queue::looping(function () {
            while (\DB::transactionLevel() > 0) {
                \DB::rollBack();
            }
        });
    }
}

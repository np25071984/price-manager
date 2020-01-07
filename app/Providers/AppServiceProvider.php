<?php

namespace App\Providers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobFailed;
use App\JobStatus;
use App\PriceGenerationStatus;

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

            switch ($event->job->getQueue()) {
                case 'pricelist_processing':
                    JobStatus::query()->updateOrCreate(
                        [
                            'contractor_id' => $job->getContractorId()
                        ],
                        [
                            'status_id' => 2,
                            'message' => 'Прайс в процессе обработки',
                        ]
                    );
                    break;
                case 'pricelist_generation':
                    PriceGenerationStatus::query()->updateOrCreate(
                        [
                            'shop_id' => $job->getShopId()
                        ],
                        [
                            'status_id' => 2,
                            'message' => 'Идет генерация прайс-листа',
                        ]
                    );
                    break;
            }

        });

        Queue::after(function (JobProcessed $event) {
            $payload = $event->job->payload();
            $job = unserialize($payload['data']['command']);

            switch ($event->job->getQueue()) {
                case 'pricelist_processing':
                    JobStatus::query()->where([
                        'contractor_id' => $job->getContractorId()
                    ])->delete();
                    break;
                case 'pricelist_generation':
                    PriceGenerationStatus::query()->where([
                            'shop_id' => $job->getShopId()
                    ])->delete();
                    break;
            }
        });

        Queue::failing(function (JobFailed $event) {
            $payload = $event->job->payload();
            $job = unserialize($payload['data']['command']);

            switch ($event->job->getQueue()) {
                case 'pricelist_processing':
                    JobStatus::query()->updateOrCreate(
                        [
                            'contractor_id' => $job->getContractorId()
                        ],
                        [
                            'status_id' => 3,
                            'message' => mb_substr($event->exception->getMessage(), 0, 1024),
                        ]
                    );
                    break;
                case 'pricelist_generation':
                    PriceGenerationStatus::query()->updateOrCreate(
                        [
                            'shop_id' => $job->getShopId()
                        ],
                        [
                            'status_id' => 3,
                            'message' => mb_substr($event->exception->getMessage(), 0, 1024),
                        ]
                    );
                    break;
            }
        });

    }
}

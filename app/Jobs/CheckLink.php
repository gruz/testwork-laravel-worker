<?php

namespace App\Jobs;

use App\Models\Link;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    private $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Link $link)
    {
        //
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $model = Link::where('id', $this->link->id)
                ->where('status', 'NEW')
                ->sharedLock() // Just in case, works without it after many tests
                ->first();
            if (!$model) {
                return;
            }
            $model->status = 'PROCESSING';
            $model->save();

            $model->sharedLock();  // Just in case, works without it after many tests
            sleep(rand(3,7)); // For testing, to see parallel
            $data = $this->checkUrl($model->url);
            $model->status = $data['status'];
            $model->http_code = $data['http_code'];
            $model->save();

        } catch (\Illuminate\Database\QueryException $th) {
            if ([ 'HY000', 5, 'database is locked', ] !== $th->errorInfo) {
                throw $th;
            }
            $this->release(rand(10, 20)); // Did several tries before, seems to be not needed
        }
    }

    private function checkUrl($url)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get($url);
            $response->headers();
            $return = [
                'status' => 'DONE',
                'http_code' => $response->status(),
            ];
        } catch (\Throwable $th) {
            $return = [
                'status' => 'ERROR',
                'http_code' => null,
            ];
        }

        return $return;
    }
}

<?php

namespace vanhenry\manager\jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OtherTablePodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($data)
    {
        $item = $data['item'];
        $configs = $data['config'];
        $action = $data['action'];
        $data = $data['data'];
        foreach ($data as $key => $dataItem) {
            foreach ($configs as $config) {
                if (strpos($key, $config['key_catch']) === 0) {
                    $class = $config['class'];
                    $method = $config[$action . '_method'];
                    (new $class)->$method($data, $item);
                    unset($data[$key]);
                }
            }
        }
    }
}

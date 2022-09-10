<?php

namespace vanhenry\manager\jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use vanhenry\manager\controller\SysController;

class SitemapPodcast implements ShouldQueue
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
    public function handle($table)
    {
        $sysController = new SysController;
        $y = date("Y");
        $m = date("m");
        $m = strpos($m, "0") === 0 ? substr($m, 1) : $m;
        $listSitemaps = \DB::select("select created_at, `table`, month(created_at)m,year(created_at) y from v_routes where is_static <> 1 or is_static is null group by month(created_at),year(created_at),`table`");
        $sysController->updateSitemapItem($table['table_map'], $y, $m);
       
        $html = \View::make('vh::more.template_sitemap', compact("listSitemaps"))->render();
        file_put_contents(public_path("sitemap.xml"), $html);
    }
}

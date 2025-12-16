<?php

namespace App\Listeners;

use App\Events\ViewCount;
use App\Models\ViewSource;
use App\Models\ViewSourceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Thanhnt\Nan\Helper\LogTha;

class ViewCountListen implements ShouldQueue
{
    /**
     * @var ViewSource
     */
    protected $viewSource;

    /**
     * @var LogTha
     */
    protected $logTha;

    // này là kiểu của job, nếu đặt giá trị thì khi chạy phải: php artisan queue:work --queue=listeners
    // public $queue = 'listeners';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ViewSource $viewSource, LogTha $logTha)
    {
        $this->viewSource = $viewSource;
        $this->logTha = $logTha;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ViewCount  $event
     * @return void
     */
    public function handle(ViewCount $event)
    {
        $source = $event->source;
        try {
            /**
             * @var ViewSource $data_source
             */
            $data_source = $this->viewSource->firstOrCreate(
                [ViewSourceInterface::ATTR_TYPE =>  $source["type"], ViewSourceInterface::ATTR_SOURCE_ID => $source["id"]],
                [ViewSourceInterface::ATTR_VALUE => 1]
            );
            $data_source->increment(ViewSourceInterface::ATTR_VALUE);
            $this->logTha->logViewCount('info', "update source for type: ".$source['type']." with id: {id}, count: {count}", ['id' => $data_source->source_id, 'count' => $data_source->value]);
            return $event;
        } catch (\Throwable $th) {
            $this->logTha->logError('warning', "add count source error: ".$th->getMessage());
        }
    }
}


// thiết lập:
// 1. Cài đặt supervisor:
    // sudo apt install supervisor
    // sudo systemctl status supervisor

// 2. tạo file chạy:
    // cd /etc/supervisor/conf.d
    // sudo nano laravel-worker.conf
    // nội dung file:
    // [program:laravel-worker]
    // process_name=%(program_name)s_%(process_num)02d
    // command=php /var/www/html/laravel1/artisan queue:work
    // autostart=true
    // autorestart=true
    // user=thanhnt
    // numprocs=8
    // redirect_stderr=true
    // stdout_logfile=/var/www/html/laravel1/storage/logs/tha/worker.log

// 3: chạy
    // sudo supervisorctl reread
    // sudo supervisorctl update
    // sudo supervisorctl start laravel-worker:*
// như này các queue sẽ được chạy tự động trên ubuntu mà không cần chạy thủ công.

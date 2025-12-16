<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

class Comment extends Model implements CommentInterface
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $table = self::TABLE_NAME;

    public function linkChildrent(){
        return $this->hasMany($this, CommentInterface::ATTR_PARENT_ID);
    }

    public function getChildrent() {
        return $this->linkChildrent()->getResults();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getChildrentPaginate($size = 12) {
        return $this->linkChildrent()->paginate($size);
    }

    function getChildrentCount() : int {
        return $this->linkChildrent()->getResults()->count();
        return 0;
    }

    function getTimeComment(){
        Carbon::setLocale('vi');
        $now = Carbon::now();
        $date = Carbon::rawCreateFromFormat('Y-m-d H:i:s', $this->updated_at);
        $timelineDay = $date->diffInDays($now);
        if ($timelineDay > 2) {
            return $date->toDateString();
        }elseif ($timelineDay == 1) {
            return 'hôm qua';
        }
        $timelineHours = $date->diffInHours($now);
        return $timelineHours > 8 ? 'hôm nay' : $date->diffForHumans(Carbon::now()); //1 giờ trước
    }

    function liked(): bool{
        if (in_array($this->id, Session::get(self::LIKED_IDS) ?: [])){
            return true;
        }
        return  false;
    }
}

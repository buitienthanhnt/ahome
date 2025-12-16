<?php

namespace Thanhnt\Ahomeglobal\Models;

use App\Models\ShareAction\ImagePathAttrModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thanhnt\Ahomeglobal\Database\Factories\RoomFactory;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

#[UseFactory(RoomFactory::class)] // define class attribute by using #(https://www.php.net/manual/en/language.attributes.overview.php)
class Room extends Model implements RoomInterface
{
    use HasFactory;
    use SoftDeletes;
    use ImagePathAttrModel;

    protected $hidden = self::HIDDEN_FIELDS;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['booked_dates', 'price'];

    /**
     * link to home of the room
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class, self::HOME_ID);
    }

    /**
     * links to list order of the room
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        // https://stackoverflow.com/questions/36249828/how-to-search-json-array-in-mysql
        return $this->hasMany(Order::class, Order::ROOM_ID, self::ID)->where(OrderInterface::DATE_FROM, '>=', substr(Carbon::now()->toISOString(), 0, 10));
    }

    /**
     * get list date has booked in the future of the room.
     */
    protected function bookedDates(): Attribute
    {
        // vendor/laravel/framework/src/Illuminate/Database/Concerns/BuildsWhereDateClauses.php
        return new Attribute(
            get: fn() => OrderTime::whereTodayOrAfter(OrderTime::DATE)
                ->whereJsonContains(OrderTimeInterface::ROOM_IDS, $this->id)
                ->select(OrderTimeInterface::DATE)
                ->get()
                ->pluck(OrderTimeInterface::DATE)
                ->toArray(),
        );
    }

    protected function price() : Attribute {
        return new Attribute(
            get: fn() => $this->attr()->where(AttrInterface::KEY, RoomInterface::ATTR_PRICE)->first()->value
        );
    }

    /**
     * get room attributes
     */
    public function attr(): HasMany
    {
        return $this->hasMany(Attr::class, Attr::SOURCE_ID, self::ID)->where(AttrInterface::TYPE, 'room');
    }

    public function type(): Attribute
    {
        return new Attribute(
            get: function (string $value) {
                foreach (RoomInterface::TYPE_VALUE as $_value) {
                    if ($_value['value'] === $value) {
                        return $_value['label'];
                    }
                }
            }
        );
    }

    public static function typeOptions()
    {
        return RoomInterface::TYPE_VALUE;
    }
}

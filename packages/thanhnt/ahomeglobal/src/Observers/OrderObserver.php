<?php

namespace Thanhnt\Ahomeglobal\Observers;

use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\OrderTime;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;

/**
 * create observer model event:
 * Tạo lắng nghe sự kiện cho Eloquent Model
 * Khai báo trong Eloquent model: #[ObservedBy([OrderObserver::class])]
 */
final class OrderObserver
{
    /**
     * Handle the Writer "created" event.
     */
    public function created(Order $order): void
    {
        $homeId = $order->{OrderInterface::HOME_ID};
        $listTimes = $order->{OrderInterface::SELECTED_TIME};
        foreach ($listTimes as $value) {
            $orderTime = OrderTime::where(OrderTimeInterface::HOME_ID, $homeId)
                ->where(OrderTimeInterface::DATE, $value)
                ->first();
            if ($orderTime) {
                $orderTime->{OrderTimeInterface::ROOM_IDS} = array_unique([...$orderTime->{OrderTimeInterface::ROOM_IDS}, $order->{OrderInterface::ROOM_ID}]);
                $orderTime->{OrderTimeInterface::ORDER_IDS} = array_unique([...$orderTime->{OrderTimeInterface::ORDER_IDS}, $order->{OrderInterface::ID}]);
                $orderTime->save();
            } else {
                OrderTime::create([
                    OrderTimeInterface::DATE => $value,
                    OrderTimeInterface::HOME_ID => $homeId,
                    OrderTimeInterface::ORDER_IDS => [$order->id],
                    OrderTimeInterface::ROOM_IDS => [$order->{OrderInterface::ROOM_ID}],
                ]);
            }
        }
    }

    /**
     * Handle the Writer "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Writer "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Writer "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Writer "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}

<?php 

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{

  public function getOrderById(int $orderId): Order;
  public function getOrders(int $perPage = 8, ?int $orderBy = null, ?array $data = null): LengthAwarePaginator;
  public function create(array $data, int $userId): Order;
  public function update(array $data, int $orderId): Order;
  public function updateStatus(int $statusId, int $orderId): Order;
  public function disable(int $orderId): Order;

}
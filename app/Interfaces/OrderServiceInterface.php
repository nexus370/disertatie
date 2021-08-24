<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{

	public function getOrderById(int $orderId, ?int $authClientId = null): Order;
	public function getOrders(int $perPage = 8, ?int $orderBy = null, ?array $data = null, ?int $authClientId = null): LengthAwarePaginator;
	public function create(array $data, int $userId = null): Order;
	public function update(array $data, Order  $order): Order;
	public function updateStatus(int $statusId, Order $order): Order;
	public function disable(Order $order): Order;
	public function linkWaiterWithOrder(int $waiterId, Order $order): Order;
}

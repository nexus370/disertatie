<?php

namespace App\Services;

use Exception;
use App\Models\Order;
use App\Models\Product;
use App\Events\NewMessage;
use App\Events\OrderCreated;
use App\Jobs\SendOrderEmailJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\TableServiceInterface;
use App\Http\Resources\Order as OrderResource;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\ProductStockServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderService implements OrderServiceInterface
{

  private $productStockService;
  private $tableService;

  public function __construct(ProductStockServiceInterface $productStockService, TableServiceInterface $tableService)
  {
    $this->productStockService = $productStockService;
    $this->tableService = $tableService;
  }

  public function getOrderById(int $orderId): Order
  {
    try {
      $order = Order::withTrashed()->findOrFail($orderId);
      return $order;
    } catch (ModelNotFoundException $mex) {
      throw new ModelNotFoundException('No order found with #' . $orderId . ' id');
    } catch (\Exception $ex) {
      throw new \Exception('Something went wrong');
    }
  }

  public function getOrders(int $perPage = 8, ?int $orderBy = null, ?array $data = null): LengthAwarePaginator
  {
    $query = Order::withTrashed();

    switch ($orderBy) {
      case 1:
        $query->orderBy('created_at', 'asc');
        break;
      case 2:
        $query->orderBy('created_at', 'desc');
        break;
      default: {
          $query->orderBy('created_at', 'desc');
          break;
        }
    }

    $query->orderBy('id', 'asc');

    $orders = $query->filter($data)->paginate($perPage);

    return $orders;
  }

  public function create(array $data, int $userId): Order
  {
    $order = new Order();

    DB::beginTransaction();
    try {
      $order = new Order;

      $order->delivery_method_id = $data['deliveryMethodId'];

      $order->status_id = 2; // recieved
      $order->staff_id = $userId;
      $order->phone_number = $data['phoneNumber'];

      if (array_key_exists('name', $data) && $data['deliveryMethodId'] ===1) {
        $order->address = $data['address'];
      }     

      if (array_key_exists('tableId', $data) && $data['deliveryMethodId'] == 3) {
        $order->table_id = $data['tableId'];
        $this->tableService->setStatus($data['tableId'], 2);
      }    

      if (array_key_exists('name', $data)) {
        $order->name = $data['name'];
      }

      if (array_key_exists('observations', $data)) {
        $order->observations =  $data['observations'];
      }

      if (array_key_exists('clientId', $data)) {
        $order->client_id = $data['clientId'];
      }

      if (array_key_exists('email', $data)) {
        $order->email = $data['email'];
      }

      $order->save();

      // add each item into order products table
      // link each item to the order
      $order = $this->addItems($order, $data['items']);

      DB::commit();

      if(isset($order->email)) {
        Queue::push(new SendOrderEmailJob($order));
      }

      broadcast(new OrderCreated($order))->toOthers();

      return $order;
    } catch (\Exception $e) {
      DB::rollBack();
      // throw new \Exception("Error Creating Order");
      throw new \Exception($e->getMessage());
    };
  }

  public function update(array $data, int $orderId): Order
  {
    try {
      $order = Order::findOrfail($orderId);

      if (array_key_exists('address', $data)) {
        $order->address = $data['address'];
      }

      if (array_key_exists('observations', $data)) {
        $order->observations = $data['observations'];
      }

      $order->save();
      $order->refresh();

      return $order;
    } catch (ModelNotFoundException $mex) {
      throw new Exception('No order found with #' . $orderId . ' id');
    } catch (\Exception $ex) {
      throw new \Exception('Faied to update order #', $orderId);
    }
  }

  public function updateStatus(int $statusId, int $orderId): Order
  {
    try {
      $order = Order::findOrFail($orderId);

      $order->status_id = $statusId;
      if($order->delivery_method_id == 3 && $statusId == 7) {
        $this->tableService->setStatus($order->table_id, 1);
      }
      $order->save();
      $order->refresh();

      return $order;
    } catch (ModelNotFoundException $mex) {
      throw new ModelNotFoundException('No order found with #' . $orderId . ' id');
    } catch (\Exception $ex) {
      // throw new \Exception('Faied to update order #' . $orderId . ' status');
      throw new \Exception($ex->getMessage());
      
    }
  }

  public function disable(int $orderId): Order
  {
    try {
      $order = Order::findOrFail($orderId);

      $this->tableService->setStatus($order->table_id, 1);

      $order = $this->removeItems($order);

      return $order;
    } catch (ModelNotFoundException $mex) {
      throw new ModelNotFoundException('No order found with #' . $orderId . ' id');
    } catch (\Exception $ex) {
      throw new Exception('Failed to disable order');
    }
  }

  private function addItems(Order $order, array $items): Order
  {
    foreach ($items as $item) {
      $product = Product::findOrFail($item['id']);
      $order->products()->attach($item['id'], [
        "product_name" => $product->name,
        "quantity" => $item['quantity'],
        "unit_price" => $product->price,
      ]);

      $this->productStockService->removeFromStock($product, $item['quantity']);
    }

    return $order;
  }

  private function removeItems(Order $order): Order
  {
    DB::transaction(function () use ($order) {
      foreach ($order->products as $product) {
        $this->productStockService->addBackToStock($product, $product->pivot->quantity);
      }

      $order->status_id = 8;
      $order->save();

      $order->delete();
    });

    $order->refresh();

    return $order;
  }
}

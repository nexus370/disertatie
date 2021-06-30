<?php


namespace App\Services;

use Exception;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Interfaces\CartServiceInterface;

class CartService implements CartServiceInterface
{
  public function getCart(int $userId = null, string $sessionId)
  {
    try {
      if(isset($userId)) {
        $cart = Cart::where('user_id', $userId)->first();
      }else {
        $cart = Cart::where('session_id', $sessionId)->first();
      }

      if(isset($cart)) {
        session(['cartId' =>$cart->id ]);
      }

      return $cart;

    } catch (\Exception $e) {
      //log exception
      // throw new Exception('Something went wrong, try again !');
      throw new Exception($e->getMessage());
    }
  }

  public function getCartItemsCount(User $user = null, string $sessionId): int 
  {
    return 0;
    // try {

    //   $itemsCount = 0;

    //   if (isset($user)) {
    //     $cart = $user->cart->itemsCount;
    //   }else {
    //     $cart = Cart::select('');
    //   }

    //   if(is_null($cart)) {
    //     $cart = $this->createCart($user, $sessionId);
    //   }

    //   return $itemsCount;

    // } catch (\Exception $e) {
    //   //log exception
    //   // throw new Exception('Something went wrong, try again !');
    //   throw new Exception($e->getMessage());
    // }
  }

  public function createCart(int $userId = null, string $sessionId): Cart 
  {
    try {
      if(session()->has('cartId') ) {
        throw new Exception('Cart already exists');
      }

      $cart = new Cart();

      if(isset($userId)) {
        $cart->user_id = $userId;
      } else {
        $cart->session_id = $sessionId;
      }

      $cart->save();

      return $cart;
  
    } catch ( \Exception $e) {
      // log exception
      // throw new Exception('Failed to create new cart');
      throw new Exception($e->getMessage());
    }
   
  }

  public function getCatItems(int $cartId): Collection
  {
    $cart = Cart::with('items')->findOrFail($cartId);

    return $cart->items;
  }

  public function addToCart(Cart $cart, int $productId, int $quantity): int
  {
    try {
      if ($cart->items->contains('id', $productId)) {
        $item = $cart->items->firstWhere('id', $productId);
        $newQuantity = $item->pivot->quantity + $quantity;
        $this->patchCartItemQuantity($cart, $productId, $newQuantity);
      } else {
        $newQuantity = $quantity;
        $cart->items()->attach($productId, ['quantity' => $newQuantity]);
      }

      return $newQuantity;

    } catch (\Exception $e) {
      // throw new Exception('Something went wrong, try again !');
      throw new Exception($e->getMessage());
    }
  }

  public function patchCartItemQuantity(Cart $cart, int $productId, int $newQuantity): void
  {
    try {
      if ($newQuantity > 0) {
        $cart->items()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
      } else {
        $this->removeItemFroMCart($cart, $productId);
      }
    } catch (\Exception $e) {
      // throw new Exception('Something went wrong, try again');
      throw new Exception($e->getMessage());
    }
  }

  public function removeItemFromCart(Cart $cart, int $productId): void
  {
    try {
      $cart->items()->detach($productId);
    } catch (\Exception $e) {
      throw new Exception('Something went wrong, try again');
    }
  }
}

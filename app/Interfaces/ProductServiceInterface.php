<?php

namespace App\Interfaces;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use phpDocumentor\Reflection\Types\Null_;

interface ProductServiceInterface
{
  public function getById(int $productId): Product;
  public function getAll(int $perPage = 8, ?int $orderBy = null, ?array $data = null): LengthAwarePaginator;
  public function create(array $data): int;

  public function patch(array $data, int $productId): void;

  public function addDiscount(int $productId, int $discountId);
  public function removeDiscount(int $productId);

  // public function addIngredient(array $data, int $productId);
  // public function patchIngredient(array $data, int $productId);
  // public function removeIngredient(array $data, int $productId);

  public function disable(int $productId): Carbon;
  public function restore(int $productId): void;
  public function destroy(int $productId): string;
  
}
<?php

namespace App\Http\Controllers\Api\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\ImageServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserImageController extends Controller
{
	private $imageService;

	public function __construct(ImageServiceInterface $imageService)
	{
		$this->imageService = $imageService;
	}

	public function store(Request $request, $id)
	{
		try {
			$storagePath = '/users_avatars/' . $id;

			$path = $this->imageService->storeImage($request->file('image'), $storagePath, 'users', 'avatar', $id);

			return response()->json(['imagePath' => $path], 201);
		} catch (\Exception $e) {
			return response()->json(['message' => $e], 500);
		}
	}

	public function destroy(Request $request, $id)
	{
		try {
			$this->imageService->deleteImage('users', 'avatar', $id, $request->imagePath);

			return response()->json(['message' => 'Image removed'], 200);
		} catch (ModelNotFoundException $e) {
			return response()->json(['message' => 'User not found'], 404);
		} catch (\Exception $e) {
			return response()->json(['message' => 'Something went wrong, try again later'], 500);
		}
	}
}
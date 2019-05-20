<?php

namespace App\Http\Controllers;

use App\Response\ApiResponse;
use Illuminate\Http\Request;
use App\Product;
use JWTAuth;

class ProductController extends Controller
{
    protected $user;
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        $products = $this->user->products()->get(['name', 'price', 'quantity'])->toArray();

        return $this->apiResponse->respondWithMessageAndPayload($products, 'Products retrieved successfully.');

    }

    public function show($id)
    {
        $product = $this->user->products()->find($id);

        if (!$product) {
            return $this->apiResponse->respondNotFound('Sorry,This product not found in our system.');
        }

        return $this->apiResponse->respondWithMessageAndPayload($product, 'Product details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        if ($this->user->products()->save($product)) {
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be added'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $product = $this->user->products()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id ' . $id . ' cannot be found'
            ], 400);
        }

        $updated = $product->fill($request->all())
            ->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $product = $this->user->products()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id ' . $id . ' cannot be found'
            ], 400);
        }

        if ($product->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product could not be deleted'
            ], 500);
        }
    }
}

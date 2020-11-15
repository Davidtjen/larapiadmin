<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::paginate();
        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        $order = Order::find($id);
        return new OrderResource($order);
    }
}
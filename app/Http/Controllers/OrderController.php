<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Order;
use Gate;
use Response;


class OrderController extends Controller
{
    public function index()
    {
        Gate::authorize('view', 'orders');
        
        $orders = Order::paginate();
        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        Gate::authorize('view', 'orders');
        
        $order = Order::find($id);
        return new OrderResource($order);
    }

    public function export()
    {
        Gate::authorize('view', 'orders');
        
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=orders.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $orders = Order::all();
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['ID', 'Name', 'Email', 'Product Title', 'Price', 'Quantity']);

            // Body
            foreach ($orders as $order) {
                fputcsv($file, [$order->id, $order->name, $order->email, '', '', '']);
                foreach ($order->orderItems as $orderItem) {
                    fputcsv($file, ['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
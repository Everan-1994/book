<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use Auth;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
	    $user = Auth::user();
		$orders = $user->orders()->orderBy('created_at', 'desc')->with('seller')->get();
		return view('orders.index', compact('orders'));
	}

	public function sellerIndex(){
        $orders = Order::paginate();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('show', $order);
        $school = $order->school;
        $seller = $order->seller;
        return view('orders.show', compact('order', 'school', 'seller'));
    }

    public function sellerShow(Order $order){
        return view('orders.show', compact('order'));
    }

    //订单确认
	public function create(Order $order, Book $book)
	{
        $this->authorize('buy', $book);
        $school = $book->school;
        $seller = $book->user;
		return view('orders.create', compact('order', 'school', 'seller', 'book'));
	}

	//订单创建
	public function store(Request $request, Order $order)
	{
	    $this->validate($request, [
	        'book_id'=>'required|numeric',
            'message'=>'nullable|max:255',
        ]);
		$book_id = $request->book_id;
		$book = Book::findOrFail($book_id);
		$this->authorize('buy', $book);
        $order = $order->createOrder(Auth::user(), $book, $request->message);
        return redirect()->to($order->payLink());
	}

	//订单发起支付
	public function pay(Order $order){
        $this->authorize('pay', $order);
        return view('orders.pay', compact('order'));
    }

    public function fakePay(Request $request){
        if(config('order_fake_pay') != 'on'){
            return abort(404);
        }
        $order = Order::where('sn' ,$request->sn)->firstOrFail();
        $out_sn = $order->createSn();
        $payed_at = Carbon::now()->toDateTimeString();
        $order->payed($out_sn, $payed_at, $order->price);
        return redirect()->route('order.index');
    }

	public function edit(Order $order)
	{
        $this->authorize('update', $order);
		return view('orders.create_and_edit', compact('order'));
	}

	public function update(OrderRequest $request, Order $order)
	{
		$this->authorize('update', $order);
		$order->update($request->all());

		return redirect()->route('orders.show', $order->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Order $order)
	{
		$this->authorize('destroy', $order);
		$order->delete();

		return redirect()->route('orders.index')->with('message', 'Deleted successfully.');
	}
}
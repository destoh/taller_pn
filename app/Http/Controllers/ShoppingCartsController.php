<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\ShoppingCart;

use App\PayPal;

use App\Order;

use Illuminate\Support\Facades\Auth;


class ShoppingCartsController extends Controller
{
    public function __construct(){
        $this->middleware("shoppingcart");
    }
    
    public function show($id){

        $shopping_cart = ShoppingCart::where('customid',$id)->first();

        $order = $shopping_cart->order();

       
        return view("shopping_carts.completed",["shopping_cart"=>$shopping_cart,"order" =>$order]);
    }


    public function checkout(Request $request){

       if (Auth::check()){ 
        $shopping_cart = $request->shopping_cart;
        $paypal = new PayPal($shopping_cart);

        $payment=$paypal->generate();

        return redirect($payment->getApprovalLink());
       }
        else{
        return view ("auth.login");
        }

    }


	public function index(Request $request){

        //$mailer =;
		
        
        $shopping_cart = $request->shopping_cart;
       
        //return(dd($shopping_cart));

        $products = $shopping_cart->products()->get();
        $in_shoping_cart=$shopping_cart->inShoppingCarts()->get();

        $total = $shopping_cart->total();

        

 		return view("shopping_carts.index",["in_shoping_cart"=>$in_shoping_cart,"products"=>$products,"total"=>$total]);
	}


}

<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class FrontPageController extends Controller
{
    public function home()
    {
        return $this->page('home');
    }

    public function products()
    {
        return $this->page('products');
    }

    public function about()
    {
        return $this->page('about');
    }

    public function contact()
    {
        return $this->page('contact');
    }

    public function serviceDetail()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please sign in or create an account to continue to service details.');
        }

        return $this->page('service-details');
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please sign in or create an account to continue to checkout.');
        }

        return $this->page('checkout');
    }

    private function page(string $activeSection)
    {
        $services = Schema::hasTable('services')
            ? Service::where('is_active', 1)->with('activeVariations')->get()
            : collect();

        return view('welcome', compact('services', 'activeSection'));
    }
}

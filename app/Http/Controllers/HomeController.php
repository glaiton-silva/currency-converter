<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Cria uma nova instância do controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a página inicial.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $latestConversions = Quotation::where('user_id', Auth::id())->orderBy('created_at', 'desc')->take(3)->get();
        return view('home', compact('latestConversions'));
    }
}

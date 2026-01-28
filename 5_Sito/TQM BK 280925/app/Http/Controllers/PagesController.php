<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * Mostra la pagina principale
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Mostra la pagina della dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('pages.dashboard');
    }

    /**
     * Mostra la pagina del manuale
     *
     * @return \Illuminate\Http\Response
     */
    public function manuale()
    {
        return view('pages.home');
    }

    /**
     * Mostra la pagina di informazioni
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        return view('pages.info');
    }
}

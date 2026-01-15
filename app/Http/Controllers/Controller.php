<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this

abstract class Controller
{
    use AuthorizesRequests; // Add this

}

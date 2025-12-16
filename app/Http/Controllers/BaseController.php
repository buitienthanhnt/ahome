<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * @var Kreait\Firebase
     */
    protected $firebase;
    protected $database;

    public function __construct(
        \App\Services\FirebaseService $firebaseService
    ) {
        $this->firebase = $firebaseService->firebase;
        $this->database = $this->firebase->createDatabase();
    }
}

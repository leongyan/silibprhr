<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

//     public function backup(){
//         // dd(1);
//         $database = 'hrdmsv3';
//         $user = 'root';
//         $pass = 'iamyourS88';
//         $host = '127.0.0.1';

//         $dir_1 = dirname(__FILE__) . '/table-'. $database .'.sql';

//         dd($dir_1);

//         exec("mysqldump -u{$user} -p{$pass} --host={$host} {$database} --result-file={$dir_1} 2>&1", $output_1);

//         var_dump($output_1);
//     }
}

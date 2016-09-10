<?php 

namespace {{App\}}Http\Controllers\Web;

use {{App\}}Http\Requests;
use Illuminate\Http\Request;
use {{App\}}Http\Controllers\Controller;

class IndexController extends Controller {

	public function index()
	{
		return view('web.index');
	}

}

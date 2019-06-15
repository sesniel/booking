<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogsController extends Controller
{
	public function index()
	{
		return view('blogs.index');
	}

	public function index2()
	{
		return view('blogs.index2');
	}

	public function single()
	{
		return view('blogs.single');
	}
}

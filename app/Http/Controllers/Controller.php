<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    abstract public function index();
    abstract public function store(Request $request);
    abstract public function show(string $id);
    abstract public function update(Request $request, string $id);
    abstract public function destroy(string $id);
}

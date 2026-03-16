<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; class PaginaController 
extends Controller { public function inicio() { return view('inicio'); } 
public function contacto() { return view('contacto'); } } 

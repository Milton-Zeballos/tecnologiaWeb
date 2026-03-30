<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $notificaciones = $user->notifications()->paginate(15);

        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida(Request $request, string $id): RedirectResponse
    {
        $n = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $n->markAsRead();

        return back();
    }

    public function marcarTodasLeidas(Request $request): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }
}

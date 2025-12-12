<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Pagination\LengthAwarePaginator;

class MessageController extends Controller
{
    public function index()
    {
        $friends = Auth::user()->friends;
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $friends->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $friends = new LengthAwarePaginator($currentItems, $friends->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return view('messages.index', compact('friends'));
    }

    public function show(User $user)
    {
        if (!Auth::user()->isFriendsWith($user)) {
            abort(403, 'You can only message your friends.');
        }

        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', Auth::id());
        })->latest()->paginate(20);

        // Mark messages as read
        Message::where('sender_id', $user->id)->where('receiver_id', Auth::id())->update(['read_at' => now()]);

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if (!Auth::user()->isFriendsWith($user)) {
            abort(403, 'You can only message your friends.');
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
        ]);

        return redirect()->route('messages.show', $user);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Pagination\LengthAwarePaginator;

class FriendController extends Controller
{
    public function index(User $user)
    {
        $friends = $user->friends;
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $friends->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $friends = new LengthAwarePaginator($currentItems, $friends->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return view('friends.index', compact('user', 'friends'));
    }

    public function sendRequest(User $user)
    {
        if (Auth::user()->hasSentFriendRequestTo($user) || $user->hasSentFriendRequestTo(Auth::user())) {
            return back()->with('error', 'Запрос в друзья уже отправлен.');
        }

        if (Auth::user()->isFriendsWith($user)) {
            return back()->with('error', 'Вы уже друзья.');
        }

        Auth::user()->sentFriendRequests()->create([
            'friend_id' => $user->id,
        ]);

        return back()->with('status', 'Запрос в друзья отправлен.');
    }

    public function acceptRequest(User $user)
    {
        $friendship = Auth::user()->friendRequests()->where('user_id', $user->id)->first();

        if (!$friendship) {
            return back()->with('error', 'Запрос в друзья не найден.');
        }

        $friendship->update(['status' => 'accepted']);

        return back()->with('status', 'Запрос в друзья принят.');
    }

    public function removeFriend(User $user)
    {
        $friendship = Auth::user()->getFriendship($user);

        if (!$friendship) {
            return back()->with('error', 'Запрос не найден.');
        }

        $status = $friendship->status;
        $friendship->delete();

        if ($status === 'accepted') {
            return back()->with('status', 'Дружба прекращена.');
        }

        return back()->with('status', 'Запрос в друзья отклонен.');
    }
}
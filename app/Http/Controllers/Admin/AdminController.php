<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function usersIndex(Request $request)
    {
        $sortableColumns = ['id', 'name', 'email', 'is_admin', 'created_at'];
        $sortColumn = $request->query('sort', 'id');
        if (!in_array($sortColumn, $sortableColumns)) {
            $sortColumn = 'id';
        }

        $sortDirection = $request->query('direction', 'desc');
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $users = User::orderBy($sortColumn, $sortDirection)
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === 1) {
            return back()->with('error', 'Статус супер-администратора (ID 1) не может быть изменен.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете отозвать права администратора у самого себя.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $message = $user->is_admin ? "Пользователю {$user->name} предоставлены права администратора." : "У пользователя {$user->name} отозваны права администратора.";

        return back()->with('status', $message);
    }

    public function destroy(User $user)
    {
        if ($user->id === 1) {
            return back()->with('error', 'Супер-администратор (ID 1) не может быть удален.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете удалить самого себя.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('status', "Пользователь {$userName} был успешно удален.");
    }
}

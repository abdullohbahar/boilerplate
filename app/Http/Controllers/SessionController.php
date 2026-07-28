<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function index(Request $request): View
    {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) use ($request) {
                return (object) [
                    'id' => $session->id,
                    'ip' => $session->ip_address,
                    'agent' => $this->parseAgent($session->user_agent),
                    'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'is_current' => $session->id === $request->session()->getId(),
                ];
            });

        return view('profile.sessions', compact('sessions'));
    }

    public function destroy(Request $request, string $sessionId): RedirectResponse
    {
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', Auth::id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return back()->with('success', 'Session revoked.');
    }

    public function destroyOthers(Request $request): RedirectResponse
    {
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return back()->with('success', 'All other sessions have been logged out.');
    }

    private function parseAgent(?string $agent): string
    {
        if (! $agent) {
            return 'Unknown';
        }

        if (str_contains($agent, 'Mobile')) {
            return 'Mobile Browser';
        }

        if (str_contains($agent, 'Chrome')) {
            return 'Chrome';
        }

        if (str_contains($agent, 'Firefox')) {
            return 'Firefox';
        }

        if (str_contains($agent, 'Safari')) {
            return 'Safari';
        }

        if (str_contains($agent, 'Edge')) {
            return 'Edge';
        }

        return 'Browser';
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Admin\Auth\Application\AdminAuthenticator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminPanelController extends Controller
{
    public function __construct(
        private AdminAuthenticator $adminAuthenticator
    ) {}

    public function login(): View
    {
        return view('pages.admin-panel.auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $authenticated = $this->adminAuthenticator->authenticate(
            $request->input('email'),
            $request->input('password')
        );

        if (!$authenticated) {
            return back()->withErrors([
                'email' => 'Invalid credentials or insufficient permissions.',
            ])->withInput($request->only('email'));
        }

        return redirect()->route('admin.home');
    }

    public function home(): View
    {
        return view('pages.admin-panel.dashboard.home');
    }

    public function products(): View
    {
        return view('pages.admin-panel.dashboard.products');
    }

    public function orders(): View
    {
        return view('pages.admin-panel.dashboard.orders');
    }

    public function blog(): View
    {
        return view('pages.admin-panel.dashboard.blog.show');
    }

    public function categories(): View
    {
        return view('pages.admin-panel.dashboard.categories');
    }

    public function attributes(): View
    {
        return view('pages.admin-panel.dashboard.attributes');
    }

    public function settings(): View
    {
        return view('pages.admin-panel.dashboard.settings');
    }

    public function formSubmissions(): View
    {
        return view('pages.admin-panel.forms.submissions');
    }

    public function formSubmissionDetail(string $id): View
    {
        return view('pages.admin-panel.forms.submission-detail', ['submissionId' => $id]);
    }

    public function logout(): RedirectResponse
    {
        $this->adminAuthenticator->logout();
        
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}
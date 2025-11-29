<?php

namespace App\Controllers;

use CodeIgniter\Shield\Models\UserModel;

class AuthController extends BaseController
{
    /**
     * Display login form
     */
    public function login()
    {
        // If user already logged in, redirect to dashboard
        if (auth()->loggedIn()) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login - Stock Opname System'
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function loginProcess()
    {
        $credentials = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        $remember = (bool) $this->request->getPost('remember');

        // Attempt to login
        $result = auth()->attempt($credentials, $remember);

        if (!$result->isOK()) {
            return redirect()->back()->with('error', $result->reason());
        }

        // Login successful - redirect based on role
        $user = auth()->user();

        if ($user->inGroup('admin')) {
            return redirect()->to('/dashboard')->with('success', 'Welcome back, Administrator!');
        }

        return redirect()->to('/dashboard')->with('success', 'Welcome back!');
    }

    /**
     * Display register form
     */
    public function register()
    {
        // If user already logged in, redirect to dashboard
        if (auth()->loggedIn()) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Register - Stock Opname System'
        ];

        return view('auth/register', $data);
    }

    /**
     * Process registration
     */
    public function registerProcess()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|strong_password',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $userData = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        try {
            $user = $userModel->save($userData);

            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Failed to create account. Please try again.');
            }

            // Get the created user
            $user = $userModel->findById($userModel->getInsertID());

            // Add user to default group (user)
            $user->addGroup('user');

            return redirect()->to('/login')->with('success', 'Account created successfully! Please login.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        auth()->logout();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Dashboard (after login)
     */
    public function dashboard()
    {
        $user = auth()->user();

        $data = [
            'title' => 'Dashboard - Stock Opname System',
            'user' => $user,
            'isAdmin' => $user->inGroup('admin'),
        ];

        return view('auth/dashboard', $data);
    }
}

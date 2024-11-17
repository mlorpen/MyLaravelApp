<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Models\User;

class FirebaseAuthController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $this->auth = Firebase::auth();
    }

    //Register

    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $user = $this->auth->createUserWithEmailAndPassword($request->email, $request->password);
            $firebaseUserId = $user->uid;
            session(['firebase_user_id' => $firebaseUserId]);
            return redirect()->route('login')->with('success', 'Registration successful! You will be redirected shortly.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'There was an issue with the registration: ' . $e->getMessage()]);
        }
    }

    //Login

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        try {
            $user = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $idToken = $user->idToken();
            $verifiedToken = $this->auth->verifyIdToken($idToken);
            $firebaseUserId = $verifiedToken->claims()->get('sub');
            session(['firebase_user_id' => $firebaseUserId]);
            return redirect()->route('/')->with('success', 'Login successful');
        } catch (\Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
            return back()->withErrors(['password' => 'The password is invalid']);
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            return back()->withErrors(['email' => 'The email does not match any account']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'There was an issue logging in. Please try again later.']);
        }
    }

    //Logout
}

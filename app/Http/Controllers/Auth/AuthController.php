<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KycDocument;
use App\Models\User;
use App\Models\UserKycDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Enums\UserRole;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.login');
    }
    public function storeLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:12',
        ]);

        // Fetch user by email
        $user = User::where('email', $request->email)->first();

        // If no user found
        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        // Check account status
        if ($user->status === 'inactive') {
            return back()->withErrors([
                'email' => 'Your account is currently inactive.',
            ])->withInput();
        }
        Log::info('User found for login', ['user_id' => $user->user_id, 'email' => $user->email]);

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        // Login the user
        Auth::login($user);
        Log::info('User logged in', [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'role' => $user->role
        ]);

        // Redirect based on role
        switch ($user->role) {
            case UserRole::ADMIN:
                return redirect()->route('admins.dashboard');
            case UserRole::LANDLORD:
                return redirect()->route('landlords.dashboard');
            case UserRole::TENANTS:
                return redirect()->route('tenants.dashboard'); // This matches your route name
            case UserRole::STAFF:
                return redirect()->route('staff.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    public function showRegisterForm()
    {
        return view('Auth.register');
    }

    public function storeRegister(Request $request)
    {
        Log::info('Starting registration process for email: ' . $request->email);

        // Validate the registration data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone_num' => 'required|string|max:20|unique:users,phone_num',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|string|in:landlord,tenants',
            'password' => ['required','string','min:12','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',],
            'doc_type' => 'required|string|in:passport,driver_license,national_id,utility_bill',
            'doc_name' => 'required|string|max:255',
            'doc_path' => 'required|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'proof_of_income' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'doc_path.max' => 'The document file must not exceed 5MB.',
            'proof_of_income.max' => 'The proof of income file must not exceed 5MB.',
        ]);

        Log::info('Registration data received', [
            'email' => $request->email, 
            'role' => $request->role,
            'has_doc_path' => $request->hasFile('doc_path'),
            'has_proof_of_income' => $request->hasFile('proof_of_income')
        ]);

        if ($validator->fails()) {
            Log::warning('Registration validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['password', 'password_confirmation'])
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Start transaction
            DB::beginTransaction();

            Log::info('Starting user creation...');

            // Create user
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'phone_num' => $request->phone_num,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'email_verified_at' => null,
                'status' => 'inactive',
            ]);

            Log::info('User created successfully', [
                'user_id' => $user->user_id, 
                'email' => $user->email,
                'role' => $user->role
            ]);

            // Handle main document upload - Store in public/images/uploadDocs
            $docFile = $request->file('doc_path');
            $docFileName = 'kyc_doc_' . time() . '_' . Str::random(10) . '.' . $docFile->getClientOriginalExtension();
            $docFilePath = $docFile->storeAs('images/uploadDocs', $docFileName, 'public');

            Log::info('Main document stored', ['path' => $docFilePath]);

            // Handle proof of income upload if provided - Store in public/images/uploadDocs
            $proofOfIncomePath = null;
            if ($request->hasFile('proof_of_income')) {
                $incomeFile = $request->file('proof_of_income');
                $incomeFileName = 'income_proof_' . time() . '_' . Str::random(10) . '.' . $incomeFile->getClientOriginalExtension();
                $proofOfIncomePath = $incomeFile->storeAs('images/uploadDocs', $incomeFileName, 'public');
                Log::info('Proof of income stored', ['path' => $proofOfIncomePath]);
            }

            // Create KYC document record
            $kycDocument = KycDocument::create([
                'user_id' => $user->user_id,
                'doc_type' => $request->doc_type,
                'doc_name' => $request->doc_name,
                'doc_path' => $docFilePath,
                'proof_of_income' => $proofOfIncomePath,
                'status' => 'pending',
                'reviewed_by' => null,
            ]);

            Log::info('KYC document created successfully', [
                'kyc_id' => $kycDocument->kyc_id, 
                'user_id' => $user->user_id,
                'doc_type' => $kycDocument->doc_type
            ]);

            DB::commit();
            Log::info('Database transaction committed successfully');

            // Log the user in
            Auth::login($user);
            Log::info('User logged in after registration', ['user_id' => $user->user_id]);

            // Redirect to dashboard with success message
            return redirect()->route('login')
                ->with('success', 'Account created successfully! Please verify your email address and wait for KYC verification.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'email' => $request->email,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Clean up uploaded files if any were created before the error
            try {
                if (isset($docFilePath) && Storage::disk('public')->exists($docFilePath)) {
                    Storage::disk('public')->delete($docFilePath);
                    Log::info('Cleaned up doc file after error');
                }
                if (isset($proofOfIncomePath) && Storage::disk('public')->exists($proofOfIncomePath)) {
                    Storage::disk('public')->delete($proofOfIncomePath);
                    Log::info('Cleaned up proof of income file after error');
                }
            } catch (\Exception $cleanupException) {
                Log::error('File cleanup failed during registration rollback: ' . $cleanupException->getMessage());
            }

            return redirect()->back()
                ->with('error', 'Registration failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
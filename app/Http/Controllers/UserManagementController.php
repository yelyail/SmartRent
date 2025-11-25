<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KycDocument;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    /**
     * Get users data for the table with filters
     */
    public function getUsers(Request $request)
    {
        $search = $request->get('search', '');
        $role = $request->get('role', 'all');
        $status = $request->get('status', 'all');

        $query = User::with(['kycDocuments' => function($query) {
            $query->latest()->limit(1);
        }]);

        // Apply filters
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_num', 'like', "%{$search}%");
            });
        }

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $users = $query->latest()->paginate(10);

        // Format the response data
        $formattedUsers = $users->getCollection()->map(function($user) {
            $latestKyc = $user->kycDocuments->first();
            
            return [
                'user_id' => $user->user_id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone_num' => $user->phone_num,
                'role' => $user->role->value,
                'status' => $user->status,
                'property' => $user->property ?? null,
                'unit' => $user->unit ?? null,
                'kyc_status' => $latestKyc ? $latestKyc->status : 'none',
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });

        return response()->json([
            'data' => $formattedUsers,
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'total' => $users->total(),
        ]);
    }

    /**
     * Get single user details
     */
    public function getUser(User $user)
    {
        $user->load('kycDocuments');
        
        return response()->json([
            'user' => $user,
            'kyc_documents' => $user->kycDocuments
        ]);
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_num' => 'required|string|max:20',
            'role' => 'required|in:' . implode(',', array_column(UserRole::cases(), 'value')),
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'address' => $validated['address'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_num' => $validated['phone_num'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
                'status' => 'active',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst($user->role->value) . ' created successfully!',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'phone_num' => 'required|string|max:20',
            'role' => 'required|in:' . implode(',', array_column(UserRole::cases(), 'value')),
        ]);

        try {
            DB::beginTransaction();

            $user->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst($user->role->value) . ' updated successfully!',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user status
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,banned'
        ]);

        try {
            $user->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => "User status updated to " . $request->status . " successfully!",
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Archive user
     */
    public function archive(User $user)
    {
        try {
            
            Log::info('Archiving user: ' . $user->user_id);
            $user->update(['status' => 'inactive']);

            return response()->json([
                'success' => true,
                'message' => ucfirst($user->role->value) . ' archived successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to archive user: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Approve KYC document
     */
    public function approveKyc(Request $request, KycDocument $kycDocument)
    {
        try {
            DB::beginTransaction();
            
            $kycDocument->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
            ]);

            // Update user status to active when KYC is approved
            $kycDocument->user->update([
                'status' => 'active'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'KYC document approved successfully! User has been activated.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve KYC: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject KYC document
     */
    public function rejectKyc(Request $request, KycDocument $kycDocument)
    {
        try {
            DB::beginTransaction();

            Log::info('Updating KYC document status to rejected');
            $kycDocument->update([
                'status' => 'reject',
                'reviewed_by' => Auth::id(),
            ]);

            Log::info('Updating user status to inactive');
            // User remains inactive when KYC is rejected
            $kycDocument->user->update([
                'status' => 'inactive'
            ]);

            DB::commit();

            Log::info('KYC document rejected successfully');

            return response()->json([
                'success' => true,
                'message' => 'KYC document rejected successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject KYC: ' . $e->getMessage(), [
                'kyc_id' => $kycDocument->kyc_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject KYC: ' . $e->getMessage()
            ], 500);
        }
    }
   /**
 * Get KYC details for a specific user
    */
  public function getKycDetails(User $user)
{
    try {
        $kycDocuments = $user->kycDocuments()->latest()->get();
        
        return response()->json([
            'success' => true,
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone_num' => $user->phone_num,
                'address' => $user->address,
                'role' => $user->role->value,
                'status' => $user->status,
            ],
            'kycDocuments' => $kycDocuments->map(function($doc) {
                return [
                    'doc_type' => $doc->doc_type,
                    'doc_name' => $doc->doc_name,
                    'doc_path' => $doc->doc_path,
                    'proof_of_income' => $doc->proof_of_income,
                    'status' => $doc->status,
                    'created_at' => $doc->created_at,
                    // Use the ENTIRE path from database in the URL
                    'doc_url' => $doc->doc_path ? url("/storage/{$doc->doc_path}") : null,
                    'income_proof_url' => $doc->proof_of_income ? url("/storage/{$doc->proof_of_income}") : null,
                ];
            })
        ]);
    } catch (\Exception $e) {
        Log::error('Error in getKycDetails: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load KYC details: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Get user statistics
     */
    public function getStats()
    {
        $stats = [
            'total_tenants' => User::where('role', UserRole::TENANTS)->count(),
            'active_tenants' => User::where('status', 'pending')->count(),
            'total_landlords' => User::where('role', UserRole::LANDLORD)->count(),
            'active_landlords' => User::where('role', UserRole::LANDLORD)->where('status', 'active')->count(),
            'pending_kyc' => KycDocument::where('status', 'pending')->count(),
            'banned_users' => User::where('status', 'banned')->count(),
        ];

        return response()->json($stats);
    }
    /**
     * Get pending KYC documents for AJAX requests
     */
    public function getPendingKyc()
    {
        $kycDocuments = KycDocument::with(['user'])
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->map(function($doc) {
                return [
                    'kyc_id' => $doc->kyc_id,
                    'user' => [
                        'first_name' => $doc->user->first_name,
                        'last_name' => $doc->user->last_name,
                        'email' => $doc->user->email,
                        'phone_num' => $doc->user->phone_num,
                    ],
                    'doc_type' => $doc->doc_type,
                    'doc_name' => $doc->doc_name,
                    'doc_path' => $doc->doc_path,
                    'proof_of_income' => $doc->proof_of_income,
                    'status' => $doc->status,
                    'created_at' => $doc->created_at,
                    // Use the ENTIRE path from database in the URL
                    'doc_url' => $doc->doc_path ? url("/storage/{$doc->doc_path}") : null,
                    'income_proof_url' => $doc->proof_of_income ? url("/storage/{$doc->proof_of_income}") : null,
                ];
            });

        return response()->json($kycDocuments);
    }
}
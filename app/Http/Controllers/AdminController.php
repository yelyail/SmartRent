<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KycDocument;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        return view('admins.dashboard');
    }
    
    public function bill()
    {
        return view('admins.bill');
    }
    
    public function analytics()
    {
        return view('admins.analytics');
    }
    
    public function maintenance()
    {
        return view('admins.maintenance');
    }
    
    public function propAssets()
    {
        return view('admins.propAssets');
    }
    
    public function userManagement(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search', '');
        $role = $request->get('role', 'all');
        $status = $request->get('status', 'all');

        // Build query
        $query = User::with(['kycDocuments' => function($query) {
            $query->latest()->limit(1); // Get only the latest KYC document
        }]);

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_num', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($role !== 'all') {
            $query->where('role', $role);
        }

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Get paginated users
        $users = $query->latest()->paginate(10);

        // If it's an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'data' => $users->items(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
            ]);
        }

        // Get stats for the cards
        $stats = [
            'total_tenants' => User::where('role', UserRole::TENANTS)->count(),
            'active_tenants' => User::where('role', UserRole::TENANTS)->where('status', 'active')->count(),
            'total_landlords' => User::where('role', UserRole::LANDLORD)->count(),
            'active_landlords' => User::where('role', UserRole::LANDLORD)->where('status', 'active')->count(),
            'pending_kyc' => KycDocument::where('status', 'pending')->count(),
            'banned_users' => User::where('status', 'banned')->count(),
        ];

        return view('admins.userManagement', compact('users', 'stats'));
    }
    
    public function properties()
    {
        return view('admins.properties');
    }
    
    public function payment()
    {
        return view('admins.payment');
    }

    /**
     * Get user statistics for AJAX requests
     */
    public function getUserStats()
    {
        $stats = [
            'total_tenants' => User::where('role', UserRole::TENANTS)->count(),
            'active_tenants' => User::where('status', 'active')->where('status', 'active')->count(),
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
                    // Add URLs for document access
                    'doc_url' => $doc->doc_path ? Storage::url('images/uploadDocs/' . $doc->doc_path) : null,
                    'income_proof_url' => $doc->proof_of_income ? Storage::url('images/uploadDocs/' . $doc->proof_of_income) : null,
                ];
            });

        return response()->json($kycDocuments);
    }
}
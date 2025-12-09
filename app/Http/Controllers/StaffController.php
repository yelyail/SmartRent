<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        // Get the logged-in staff user
        $user = Auth::user();
        
        // Get maintenance statistics for this staff
        $maintenanceStats = $this->getMaintenanceStats($user);
        
        // Get recent maintenance activities
        $recentActivities = $this->getRecentActivities($user);
        
        // Get properties assigned to this staff
        $assignedProperties = $this->getAssignedProperties($user);
        
        // Get upcoming maintenance tasks
        $upcomingTasks = $this->getUpcomingTasks($user);
        
        // Get urgent maintenance requests
        $urgentRequests = $this->getUrgentRequests($user);
        
        return view('staff.dashboard', [
            'maintenanceStats' => $maintenanceStats,
            'recentActivities' => $recentActivities,
            'assignedProperties' => $assignedProperties,
            'upcomingTasks' => $upcomingTasks,
            'urgentRequests' => $urgentRequests,
            'user' => $user
        ]);
    }

    private function getMaintenanceStats($user)
    {
        $totalAssigned = MaintenanceRequest::where('assigned_staff_id', $user->user_id)->count();
        $inProgress = MaintenanceRequest::where('assigned_staff_id', $user->user_id)
            ->where('status', 'in_progress')
            ->count();
        $completedThisMonth = MaintenanceRequest::where('assigned_staff_id', $user->user_id)
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->count();
        $urgentPending = MaintenanceRequest::where('assigned_staff_id', $user->user_id)
            ->whereIn('priority', ['urgent', 'high'])
            ->where('status', '!=', 'completed')
            ->count();
        
        // Calculate completion rate
        $completionRate = $totalAssigned > 0 
            ? round(($completedThisMonth / $totalAssigned) * 100, 1)
            : 0;
        
        return [
            'total_assigned' => $totalAssigned,
            'in_progress' => $inProgress,
            'completed_this_month' => $completedThisMonth,
            'urgent_pending' => $urgentPending,
            'completion_rate' => $completionRate
        ];
    }

    private function getRecentActivities($user)
    {
        return MaintenanceRequest::with(['unit.property', 'user'])
            ->where('assigned_staff_id', $user->user_id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->request_id,
                    'title' => $request->title,
                    'property' => optional(optional($request->unit)->property)->property_name ?? 'Unknown',
                    'unit' => optional($request->unit)->unit_num ?? 'N/A',
                    'status' => $request->status,
                    'priority' => $request->priority,
                    'updated_at' => $request->updated_at,
                    'icon' => $this->getActivityIcon($request->status, $request->priority),
                    'icon_color' => $this->getActivityColor($request->status, $request->priority)
                ];
            });
    }

    private function getAssignedProperties($user)
    {
        // Get unique properties from assigned maintenance requests
        return MaintenanceRequest::with('unit.property')
            ->where('assigned_staff_id', $user->user_id)
            ->whereIn('status', ['in_progress', 'pending'])
            ->get()
            ->groupBy(function ($request) {
                return optional(optional($request->unit)->property)->property_id ?? 'unknown';
            })
            ->map(function ($requests) {
                $property = optional($requests->first()->unit)->property;
                if (!$property) return null;
                
                return [
                    'property_id' => $property->property_id,
                    'property_name' => $property->property_name,
                    'address' => $property->property_address,
                    'active_requests' => $requests->count(),
                    'urgent_requests' => $requests->whereIn('priority', ['urgent', 'high'])->count()
                ];
            })
            ->filter()
            ->values()
            ->take(3);
    }

    private function getUpcomingTasks($user)
    {
        return MaintenanceRequest::with(['unit.property', 'user'])
            ->where('assigned_staff_id', $user->user_id)
            ->where('status', 'in_progress')
            ->orderBy('requested_at', 'asc')
            ->limit(3)
            ->get()
            ->map(function ($request) {
                $request->days_open = now()->diffInDays($request->requested_at);
                $request->property_name = optional(optional($request->unit)->property)->property_name ?? 'Unknown';
                $request->unit_number = optional($request->unit)->unit_num ?? 'N/A';
                $request->tenant_name = optional($request->user)->first_name . ' ' . optional($request->user)->last_name;
                return $request;
            });
    }

    private function getUrgentRequests($user)
    {
        return MaintenanceRequest::with(['unit.property', 'user'])
            ->where('assigned_staff_id', $user->user_id)
            ->whereIn('priority', ['urgent', 'high'])
            ->where('status', '!=', 'completed')
            ->orderBy('priority', 'desc')
            ->orderBy('requested_at', 'asc')
            ->limit(3)
            ->get()
            ->map(function ($request) {
                $request->days_open = now()->diffInDays($request->requested_at);
                $request->property_name = optional(optional($request->unit)->property)->property_name ?? 'Unknown';
                $request->unit_number = optional($request->unit)->unit_num ?? 'N/A';
                return $request;
            });
    }

    private function getActivityIcon($status, $priority)
    {
        if ($priority == 'urgent') {
            return 'fas fa-exclamation-triangle';
        } elseif ($priority == 'high') {
            return 'fas fa-exclamation-circle';
        } elseif ($status == 'completed') {
            return 'fas fa-check-circle';
        } elseif ($status == 'in_progress') {
            return 'fas fa-tools';
        } else {
            return 'fas fa-clock';
        }
    }

    private function getActivityColor($status, $priority)
    {
        if ($priority == 'urgent') {
            return 'red';
        } elseif ($priority == 'high') {
            return 'orange';
        } elseif ($status == 'completed') {
            return 'green';
        } elseif ($status == 'in_progress') {
            return 'blue';
        } else {
            return 'yellow';
        }
    }

    public function maintenance()
    {
        // Get the logged-in user
        $user = Auth::user();

        $maintenanceRequests = MaintenanceRequest::with([
                'unit.property',
                'user',
                'assignedStaff', 
            ])
            ->where('assigned_staff_id', $user->user_id)
            ->orderByRaw("
                CASE 
                    WHEN status = 'in_progress' THEN 1
                    WHEN status = 'pending' THEN 2
                    WHEN status = 'completed' THEN 3
                    WHEN status = 'cancelled' THEN 4
                    ELSE 5
                END
            ")
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($request) {
                // Calculate days open (always calculate it)
                $request->days_open = $request->requested_at ? now()->diffInDays($request->requested_at) : 0;
                $request->property_name = optional(optional($request->unit)->property)->property_name ?? 'Unknown Property';
                $request->unit_number = optional($request->unit)->unit_num ?? 'N/A';
                
                // Get assigned staff name
                $request->assigned_staff_name = optional($request->assignedStaff)->first_name . ' ' . optional($request->assignedStaff)->last_name;
                
                // Get tenant name (from user relationship)
                $request->tenant_name = optional($request->user)->first_name . ' ' . optional($request->user)->last_name;
                
                // Get completion date if exists - safely handle null
                $request->completion_date = $request->completed_at ? $request->completed_at->format('M d, Y') : null;
                
                return $request;
            });
        
        // Calculate statistics
        $stats = [
            'total_tasks' => $maintenanceRequests->count(),
            'in_progress' => $maintenanceRequests->where('status', 'in_progress')->count(),
            'completed' => $maintenanceRequests->where('status', 'completed')->count(),
            'pending' => $maintenanceRequests->where('status', 'pending')->count(),
            'cancelled' => $maintenanceRequests->where('status', 'cancelled')->count(),
            'high_priority' => $maintenanceRequests->whereIn('priority', ['urgent', 'high'])->count(),
            'urgent' => $maintenanceRequests->where('priority', 'urgent')->count(),
            'total_cost' => 0,
            'avg_days_open' => $maintenanceRequests->where('status', '!=', 'completed')->avg('days_open') ?? 0
        ];
        
        return view('staff.maintenance', [
            'maintenanceRequests' => $maintenanceRequests,
            'stats' => $stats
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'completion_notes' => 'nullable|string|max:1000'
        ]);
        
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        
        // Check if the user is assigned to this request
        if ($maintenanceRequest->assigned_staff_id != Auth::id()) {
            return back()->with('error', 'You are not assigned to this maintenance request.');
        }
        
        // Update status
        $maintenanceRequest->status = $validated['status'];
        
        // If completing, set completed_at
        if ($validated['status'] == 'completed') {
            $maintenanceRequest->completed_at = now();
        }
        
        // If moving from completed to another status, clear completed_at
        if ($maintenanceRequest->getOriginal('status') == 'completed' && $validated['status'] != 'completed') {
            $maintenanceRequest->completed_at = null;
        }
        
        $maintenanceRequest->save();
        
        return back()->with('success', 'Maintenance request status updated successfully.');
    }
}
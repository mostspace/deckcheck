<?php

use Illuminate\Support\Carbon;

// Global Auth Scoping for User's Active Vessel
function currentVessel() {
    $user = auth()->user();
    if (!$user) return null;
    
    // For system users, try to get from session or return first accessible vessel
    if (in_array($user->system_role, ['superadmin', 'staff', 'dev'])) {
        // Check if there's a vessel in session (for when they switch to a specific vessel)
        if (session()->has('active_vessel_id')) {
            $vessel = \App\Models\Vessel::find(session('active_vessel_id'));
            if ($vessel && $user->hasSystemAccessToVessel($vessel)) {
                \Log::info('System user vessel from session', [
                    'user_id' => $user->id,
                    'vessel_id' => $vessel->id,
                    'session_vessel_id' => session('active_vessel_id')
                ]);
                return $vessel;
            }
        }
        
        // Return first accessible vessel for system users
        $accessibleVessels = $user->getAccessibleVessels();
        if ($accessibleVessels && $accessibleVessels->count() > 0) {
            $firstVessel = $accessibleVessels->first();
            \Log::info('System user vessel from accessible vessels', [
                'user_id' => $user->id,
                'vessel_id' => $firstVessel->id,
                'total_accessible' => $accessibleVessels->count()
            ]);
            return $firstVessel;
        }
        
        \Log::info('System user no accessible vessels', [
            'user_id' => $user->id,
            'accessible_vessels_count' => $accessibleVessels ? $accessibleVessels->count() : 0
        ]);
        return null;
    }
    
    // Regular users get their active vessel
    return $user->activeVessel();
}

function currentUserBoarding() {
    $user = auth()->user();
    $vessel = currentVessel();
    
    if (!$user || !$vessel) return null;
    
    // For system users without explicit boarding, return a mock boarding object
    if (in_array($user->system_role, ['superadmin', 'staff', 'dev'])) {
        $boarding = $user->boardings()->where('vessel_id', $vessel->id)->first();
        if (!$boarding) {
            // Create a temporary boarding object for system users
            $boarding = new \App\Models\Boarding();
            $boarding->vessel_id = $vessel->id;
            $boarding->user_id = $user->id;
            $boarding->status = 'active';
            $boarding->access_level = 'admin';
            $boarding->is_primary = true;
        }
        return $boarding;
    }
    
    // Regular users get their actual boarding
    return $user->boardings()->where('vessel_id', $vessel?->id)->first();
}


if (!function_exists('frequency_label_class')) {
    function frequency_label_class(?string $frequency): string
    {
        return match (strtolower($frequency)) {
            // Common intervals — vibrant, varied
            'daily'         => 'bg-[#e0f2fe] text-[#0369a1]', // blue
            'bi-weekly'     => 'bg-[#b4faee] text-[#0f766e]', // teal
            'weekly'        => 'bg-[#beffba] text-[#0f766e]', // green
            'monthly'       => 'bg-[#f3e8ff] text-[#7e22ce]', // lavender
            'quarterly'     => 'bg-[#fef3c7] text-[#92400e]', // amber
            'bi-annually'   => 'bg-[#ffedd5] text-[#c2410c]', // orange
            'annual'        => 'bg-[#ffe4e6] text-[#be123c]', // rose

            // Less common — subtle purples/blues
            '2-yearly'      => 'bg-[#ede9fe] text-[#6b21a8]', // violet
            '3-yearly'      => 'bg-[#e0e7ff] text-[#3730a3]', // indigo
            '5-yearly'      => 'bg-[#f0f9ff] text-[#0e7490]', // cyan
            '6-yearly'      => 'bg-[#f5f3ff] text-[#7e22ce]', // purple
            '10-yearly'     => 'bg-[#fdf2f8] text-[#be185d]', // pink
            '12-yearly'     => 'bg-[#f3f4f6] text-[#374151]', // slate

            default         => 'bg-gray-100 text-gray-500',   // fallback
        };
    }
}

if (!function_exists('facilitator_label_class')) {
    function facilitator_label_class(?string $facilitator): string
    {
        return match (strtolower($facilitator)) {
            'crew'     => 'bg-blue-100 text-blue-800',
            'service provider' => 'bg-yellow-100 text-yellow-800',
            default    => 'bg-gray-100 text-gray-700',
        };
    }
}

if (!function_exists('applicable_to_label_class')) {
    function applicable_to_label_class(?string $value): string
    {
        return match ($value) {
            'All Equipment'      => 'bg-sky-100 text-sky-800',
            'Specific Equipment' => 'bg-orange-100 text-orange-800',
            'Conditional'        => 'bg-indigo-100 text-indigo-800',
            default              => 'bg-gray-100 text-gray-700',
        };
    }
}

// Equipment Status
if (!function_exists('status_label_class')) {
    function status_label_class(?string $status): string
    {
        return match ($status) {
            'In Service'    => 'bg-green-100 text-green-800 border-green-800',
            'Out of Service'=> 'bg-yellow-100 text-yellow-800 border-yellow-800',
            'Inoperable'    => 'bg-red-100 text-red-800 border-red-800',
            'Archived'      => 'bg-gray-200 text-gray-700 border-gray-700',
            'Unknown'       => 'bg-gray-100 text-gray-700 border-gray-700',
            default         => 'bg-gray-100 text-gray-700 border-gray-700',
        };
    }
}

// Equipment Status Cards
if (!function_exists('status_label_icon')) {
    function status_label_icon(?string $status): string
    {
        return match ($status) {
            'In Service'    => 'fa-solid fa-circle-check',
            'Out of Service'=> 'fa-solid fa-circle-exclamation',
            'Inoperable'    => 'fa-solid fa-circle-xmark',
            'Archived'      => 'fa-solid fa-circle-minus',
            'Unknown'       => 'fa-solid fa-circle-exclamation',
            default         => 'fa-solid fa-circle-exclamation',
        };
    }
}

if (!function_exists('last_completed_badge')) {
    function last_completed_badge(?string $date, string $placeholder = 'Pending First Completion'): string
    {
        if ($date) {
            return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-[#f2f4f7] text-[#344053]">'
                . \Carbon\Carbon::parse($date)->format('M j, Y') .
                '</span>';
        }

        return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800 italic">'
            . $placeholder .
            '</span>';
    }
}

if (!function_exists('next_due_badge')) {
    function next_due_badge(?string $date, string $placeholder = 'To Be Determined'): string
    {
        if ($date) {
            return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-[#ecfdf3] text-[#027a48]">'
                . \Carbon\Carbon::parse($date)->format('M j, Y') .
                '</span>';
        }

        return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800 italic">'
            . $placeholder .
            '</span>';
    }
}

// Work Order Status
if (!function_exists('status_badge')) {
    function status_badge(?string $status): string
    {
        return match (strtolower($status)) {
            // Deficiency statuses (same styling as deficiency show page)
            'open' => '<span class="px-2 py-1 text-xs font-medium bg-[#fef3f2] text-[#b42318] rounded-full">Open</span>',
            'waiting' => '<span class="px-2 py-1 text-xs font-medium bg-[#fef7ed] text-[#c4320a] rounded-full">Waiting</span>',
            'resolved' => '<span class="px-2 py-1 text-xs font-medium bg-[#ecfdf3] text-[#027a48] rounded-full">Resolved</span>',
            
            // Work order statuses (keep existing styling)
            'scheduled' => '<span class="border px-2 py-1 text-xs font-medium rounded-full bg-[#f0f9ff] text-[#026aa2] border-[#026aa2]">Scheduled</span>',
            'in_progress' => '<span class="border px-2 py-1 text-xs font-medium rounded-full bg-[#fef6fb] text-[#c11574] border-[#c11574]">In Progress</span>',
            'completed' => '<span class="border px-2 py-1 text-xs font-medium rounded-full bg-[#ecfdf3] text-[#027a48] border-[#027a48]">Completed</span>',
            'overdue' => '<span class="border px-2 py-1 text-xs font-medium rounded-full bg-[#fef3f2] text-[#b42318] border-[#b42318]">Overdue</span>',
            'flagged' => '<span class="border px-2 py-1 text-xs font-medium rounded-full bg-[#fffaeb] text-[#b54708] border-[#b54708]">Flagged</span>',
            'deferred' => '<span class="border px-2 py-1 text-xs font-medium rounded-full bg-[#f9f5ff] text-[#6941c6] border-[#6941c6]">Deferred</span>',
            default => '<span class="px-2 py-1 text-xs font-medium bg-[#f3f4f6] text-[#475466] rounded-full">Unknown</span>',
        };
    }
}

function deficiency_status_class($status)
{
    return match ($status) {
        'open' => 'bg-[#fef3f2] text-[#b42318]',
        'waiting' => 'bg-[#fff6ed] text-[#c2410c]',
        'resolved' => 'bg-[#ecfdf3] text-[#027a48]',
        default => 'bg-[#f3f4f6] text-[#344053]',
    };
}

if (!function_exists('priority_badge')) {
    function priority_badge(?string $priority): string
    {
        return match (strtolower($priority)) {
            'high' => '<span class="px-2 py-1 text-xs font-medium bg-[#fef3f2] text-[#b42318] rounded-full">High</span>',
            'medium' => '<span class="px-2 py-1 text-xs font-medium bg-[#fef7ed] text-[#c4320a] rounded-full">Medium</span>',
            'low' => '<span class="px-2 py-1 text-xs font-medium bg-[#ecfdf3] text-[#027a48] rounded-full">Low</span>',
            default => '<span class="px-2 py-1 text-xs font-medium bg-[#f1f5f9] text-[#475466] rounded-full">Unset</span>',
        };
    }
}

if (!function_exists('work_order_due_badge')) {
    function work_order_due_badge($workOrder): string
    {
        if ($workOrder->status === 'completed') {
            return '<span class="text-sm text-[#667085]">—</span>';
        }

        if (! $workOrder?->due_date) {
            return '<span class="text-sm font-medium text-[#dc6803]">Pending</span>';
        }

        $due = Carbon::parse($workOrder->due_date)->startOfDay();
        $today = Carbon::now()->startOfDay();
        $daysOverdue = $today->diffInDays($due, false); // Negative if overdue

        if ($workOrder->status === 'open' || 'flagged' || 'in_progess') {
            if ($daysOverdue < 0) {
                return '<span class="text-sm font-medium text-[#b42318]">'
                    . $due->format('M j, Y')
                    . ' <span class="ml-1 italic text-[#b42318]">(' . abs($daysOverdue) . ' day' . (abs($daysOverdue) !== 1 ? 's' : '') . ' overdue)</span>'
                    . '</span>';
            }

            if ($daysOverdue === 0) {
                return '<span class="text-sm font-medium text-[#dc6803]">' . $due->format('M j, Y') . '</span>';
            }

            return '<span class="text-sm font-medium text-[#027a48]">' . $due->format('M j, Y') . '</span>';
        }

        if ($workOrder->status === 'scheduled') {
            return '<span class="text-sm italic text-[#667085]">' . $due->format('M j, Y') . '</span>';
        }

        return '<span class="text-sm text-[#667085]">—</span>';
    }
}

if (!function_exists('work_order_completed_badge')) {
    function work_order_completed_badge($workOrder): string
    {
        if (! $workOrder?->completed_at) {
            return '<span class="text-sm font-medium text-[#dc6803]">Pending</span>';
        }

        return '<span class="text-sm text-[#0f1728]">'
            . \Carbon\Carbon::parse($workOrder->completed_at)->format('M j, Y') .
            '</span>';
    }
}
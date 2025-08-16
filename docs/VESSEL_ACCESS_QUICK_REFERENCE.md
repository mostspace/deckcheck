# Vessel Access System - Quick Reference Guide

## Quick Start

### For System Users (Admin/Staff/Dev)
1. **Enter Vessel**: Go to `/admin/vessels` → Click "Enter Vessel"
2. **Switch Vessel**: Use user modal in sidebar or "Switch Vessel" button on dashboard
3. **View Context**: Dashboard shows current vessel and navigation options

### For Regular Users
1. **Vessel Access**: Must have active boarding record
2. **Switch Vessel**: Use user modal → Select from boarded vessels
3. **Permission Scope**: Based on boarding access level and role

## Key Methods

### User Model
```php
// Check vessel access
$user->hasSystemAccessToVessel($vessel);

// Get accessible vessels
$user->getAccessibleVessels();

// Check system role
$user->hasSystemAccess('superadmin');
```

### Helper Functions
```php
// Get current vessel
$vessel = currentVessel();

// Get current boarding
$boarding = currentUserBoarding();
```

## Route Protection

### Apply Middleware
```php
Route::get('/feature/{vessel}', [Controller::class, 'method'])
    ->middleware('vessel.access');
```

### Check Access in Controller
```php
if (!auth()->user()->hasSystemAccessToVessel($vessel)) {
    abort(403, 'Access denied to this vessel');
}
```

## Common Patterns

### 1. Vessel Access Check
```php
public function show(Vessel $vessel)
{
    if (!auth()->user()->hasSystemAccessToVessel($vessel)) {
        abort(403, 'Access denied to this vessel');
    }
    
    // Continue with logic...
}
```

### 2. Current Vessel Usage
```php
public function index()
{
    $vessel = currentVessel();
    
    if (!$vessel) {
        abort(404, 'No vessel assigned.');
    }
    
    // Use $vessel for queries...
}
```

### 3. System User Handling
```php
if (in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev'])) {
    // System user logic
} else {
    // Regular user logic
}
```

## Error Messages

### Standard Responses
- **403**: Access denied to vessel
- **404**: Vessel not found
- **Info**: No vessel selected (system users)

### Flash Messages
```php
return redirect()->route('dashboard')
    ->with('success', 'Vessel switched successfully')
    ->with('info', 'No vessel selected')
    ->with('error', 'Access denied');
```

## Testing Checklist

### System Users
- [ ] Can access admin vessel list
- [ ] Can enter any vessel
- [ ] Dashboard shows vessel indicator
- [ ] Can switch between vessels
- [ ] Session persists vessel selection

### Regular Users
- [ ] Can only access boarded vessels
- [ ] Vessel switching works via boarding
- [ ] Access level enforcement works
- [ ] Primary vessel selection works

### Security
- [ ] Unauthorized vessel access blocked
- [ ] Middleware properly applied
- [ ] Error messages consistent
- [ ] Logging working correctly

## Troubleshooting

### "Enter Vessel" Not Working
1. Check route middleware: `php artisan route:list --name=vessel.switch`
2. Clear caches: `php artisan route:clear && php artisan config:clear`
3. Check browser console for JavaScript errors
4. Verify database connection

### Access Denied Errors
1. Check user system role
2. Verify vessel exists
3. Ensure middleware applied to route
4. Check `hasSystemAccessToVessel()` logic

### Dashboard Issues
1. Check session storage
2. Verify `currentVessel()` helper
3. Check vessel data in database
4. Review flash message display

## File Locations

### Core Files
- **User Model**: `app/Models/User.php`
- **Helper Functions**: `app/helpers.php`
- **Vessel Access Middleware**: `app/Http/Middleware/VesselAccess.php`
- **Vessel Switch Controller**: `app/Http/Controllers/VesselSwitchController.php`

### Views
- **Dashboard**: `resources/views/dashboard.blade.php`
- **Admin Vessel Index**: `resources/views/admin/data/vessel/index.blade.php`
- **Admin Vessel Show**: `resources/views/admin/data/vessel/show.blade.php`
- **User Modal**: `resources/views/components/main/user-modal.blade.php`

### Routes
- **Main Routes**: `routes/web.php`
- **Admin Routes**: `routes/admin.php`
- **Middleware Registration**: `bootstrap/app.php`

## Common Commands

```bash
# Clear caches
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# Check routes
php artisan route:list --name=vessel
php artisan route:list --name=admin

# Test database
php artisan tinker --execute="echo 'Vessels: ' . App\Models\Vessel::count();"

# Check middleware
php artisan route:list --middleware=vessel.access
```

## Best Practices

### 1. Always Check Access
```php
// Good
if (!auth()->user()->hasSystemAccessToVessel($vessel)) {
    abort(403, 'Access denied to this vessel');
}

// Bad - Don't skip access checks
// Logic without permission validation
```

### 2. Use Helper Functions
```php
// Good
$vessel = currentVessel();

// Bad - Don't manually query
$vessel = auth()->user()->activeVessel();
```

### 3. Consistent Error Handling
```php
// Good - Consistent error messages
abort(403, 'Access denied to this vessel');

// Bad - Inconsistent error messages
abort(404, 'Vessel not found'); // Wrong HTTP status
```

### 4. Log Important Actions
```php
// Good - Log vessel access
\Log::info('User accessed vessel', [
    'user_id' => auth()->id(),
    'vessel_id' => $vessel->id
]);

// Bad - No logging
// Silent vessel access
```

## Future Considerations

### 1. Performance
- Consider caching vessel access results
- Optimize database queries for large vessel counts
- Implement lazy loading for vessel data

### 2. Security
- Add rate limiting for vessel switching
- Implement audit logging for all vessel access
- Consider IP-based access restrictions

### 3. Scalability
- Support for vessel hierarchies
- Cross-vessel data sharing capabilities
- Multi-tenant architecture considerations

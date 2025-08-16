# User Management System - Admin Panel

## Overview
The User Management system provides administrators with comprehensive tools to view, filter, and manage regular users (system_role: 'user') across all vessels in the DeckCheck system. Staff members (superadmin, staff, dev) are managed separately in the Staff management area.

## Features

### 1. User Index Page (`/admin/users`)
- **Comprehensive User Table**: Displays all users with key information
- **Advanced Filtering**: Filter by status, vessel, system role, and search terms
- **Statistics Dashboard**: Shows total users, active users, primary users, and crew members
- **Responsive Design**: Works on desktop and mobile devices

### 2. User Details Page (`/admin/users/{user}`)
- **Detailed Profile**: Complete user information and profile picture
- **Vessel Access**: Shows all vessels the user has access to with status indicators
- **Activity Tracking**: Placeholder for future activity monitoring features

## Filtering Options

### Status Filters
- **All Users**: Shows all users regardless of status
- **Active Users**: Users with active boarding status
- **Inactive Users**: Users without active boardings
- **Primary Users**: Users who are primary vessel owners
- **Crew Members**: Users with crew member status

### Additional Filters
- **Vessel**: Filter users by specific vessel
- **Search**: Search by name or email address

## Navigation

The User Management system is accessible from the admin sidebar:
- **Location**: Management section → User Management
- **Route**: `admin.users.index`
- **Icon**: User gear icon

## Database Relationships

The system leverages the following relationships:
- **User** ↔ **Boarding** (pivot table)
- **Boarding** ↔ **Vessel**
- **User** has many **Boardings**
- **Vessel** has many **Boardings**

## Key Features

### User Status Indicators
- Green dot: Active boarding status
- Gray dot: Inactive boarding status
- Primary badge: User is primary vessel owner
- Crew badge: User has crew member status

### Action Buttons
- **View**: Navigate to user details page
- **Edit**: Edit user information (placeholder)
- **Manage Access**: Manage user permissions (placeholder)
- **Suspend**: Suspend user access (placeholder)

### Statistics Cards
- **Total Regular Users**: Count of all registered regular users
- **Active Regular Users**: Regular users with active vessel access
- **Primary Vessel Users**: Regular users who own vessels
- **Crew Members**: Regular users with crew member status

## Technical Implementation

### Controller
- **File**: `app/Http/Controllers/Admin/UserManagementController.php`
- **Methods**: `index()`, `show()`
- **Features**: Eager loading, relationship counting, advanced filtering, user role restriction (system_role: 'user')

### Views
- **Index**: `resources/views/admin/users/index.blade.php`
- **Show**: `resources/views/admin/users/show.blade.php`
- **Layout**: Extends `layouts/admin.blade.php`

### Routes
- **Index**: `GET /admin/users` → `admin.users.index`
- **Show**: `GET /admin/users/{user}` → `admin.users.show`

### Pagination
- **Custom View**: `resources/views/vendor/pagination/tailwind.blade.php`
- **Styling**: Tailwind CSS with dark theme
- **Features**: Responsive design, accessibility support

## Future Enhancements

### Planned Features
- User editing and management
- Access control management
- User suspension/reactivation
- Activity logging and monitoring
- Bulk user operations
- Export functionality
- User invitation system

### Technical Improvements
- Real-time user status updates
- Advanced search with multiple criteria
- User activity analytics
- Audit logging
- API endpoints for external integrations

## Usage Examples

### Filtering Active Users on a Specific Vessel
1. Navigate to User Management
2. Select "Active Users" from Status dropdown
3. Choose specific vessel from Vessel dropdown
4. Click "Apply Filters"

### Viewing User Details
1. Find user in the table
2. Click the eye icon (View button)
3. Review comprehensive user information
4. Use back button to return to user list

### Searching for Specific Users
1. Use the search box to enter name or email
2. Apply additional filters if needed
3. Results update automatically
4. Clear filters to reset search

## Security Considerations

- **Admin Middleware**: All routes protected by `AdminOnly` middleware
- **User Isolation**: Users can only see their own data in regular views
- **Role-Based Access**: System role determines access levels
- **Audit Trail**: All admin actions should be logged (future enhancement)

## Troubleshooting

### Common Issues
- **No Users Displayed**: Check database connection and user table
- **Filter Not Working**: Verify filter parameters and database relationships
- **Pagination Errors**: Ensure custom pagination view is properly configured

### Performance Tips
- **Eager Loading**: Relationships are properly eager loaded to avoid N+1 queries
- **Indexing**: Ensure proper database indexes on frequently filtered fields
- **Caching**: Consider implementing caching for user statistics (future enhancement)

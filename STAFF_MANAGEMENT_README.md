# Staff Management System - Admin Panel

## Overview
The Staff Management system provides administrators with comprehensive tools to view, filter, and manage system staff members (superadmin, staff, dev) in the DeckCheck system. This is separate from the User Management system which handles regular vessel users.

## Features

### 1. Staff Index Page (`/admin/staff`)
- **Comprehensive Staff Table**: Displays all staff members with key information
- **Advanced Filtering**: Filter by system role, status, and search terms
- **Statistics Dashboard**: Shows total staff, role breakdowns, and vessel access counts
- **Permissions Scope Column**: Clear indication of what each role can access
- **Responsive Design**: Works on desktop and mobile devices

### 2. Staff Details Page (`/admin/staff/{user}`)
- **Detailed Profile**: Complete staff member information and profile picture
- **Permissions Scope**: Detailed breakdown of what each role can access
- **Vessel Access**: Shows all vessels the staff member has access to
- **Activity Tracking**: Placeholder for future activity monitoring features

### 3. Staff Edit Page (`/admin/staff/{user}/edit`)
- **Edit Information**: Update names, email, phone, and system role
- **Role Management**: Change staff member's system role with descriptions
- **Validation**: Form validation and error handling

## System Roles & Permissions

### Super Administrator (`superadmin`)
- **Full System Access**: Complete control over all system functions
- **User Management**: Create, edit, and manage all user accounts
- **Data Management**: Full access to all vessel data and system logs
- **System Configuration**: Modify system settings and security policies
- **Color Code**: Red theme with crown icon

### Staff Member (`staff`)
- **Vessel Management**: Full access to vessel data and crew management
- **Maintenance Tools**: Access to maintenance schedules and work orders
- **Reports & Analytics**: Generate reports and view business intelligence
- **Limited User Management**: Manage regular user accounts and permissions
- **Color Code**: Purple theme with user-tie icon

### Developer (`dev`)
- **System Development**: Access to development tools and API endpoints
- **Debug & Testing**: Access to testing environments and error logs
- **Technical Operations**: Monitor system performance and metrics
- **Data Access**: Read access to all system data for development
- **Color Code**: Green theme with code icon

## Filtering Options

### System Role Filters
- **All Roles**: Shows all staff members regardless of role
- **Superadmin**: Only super administrators
- **Staff**: Only staff members
- **Dev**: Only developers

### Status Filters
- **All Staff**: Shows all staff members
- **Active Vessel Access**: Staff with active vessel boardings
- **Inactive Vessel Access**: Staff with inactive vessel boardings
- **Has Vessel Access**: Staff with any vessel access
- **No Vessel Access**: Staff without any vessel access

### Additional Filters
- **Search**: Search by name or email address

## Navigation

The Staff Management system is accessible from the admin sidebar:
- **Location**: Management section → Staff
- **Route**: `admin.staff.index`
- **Icon**: Users icon

## Database Relationships

The system leverages the same relationships as User Management:
- **User** ↔ **Boarding** (pivot table)
- **Boarding** ↔ **Vessel**
- **User** has many **Boardings**
- **Vessel** has many **Boardings**

## Key Features

### Staff Status Indicators
- **Role Badges**: Color-coded badges for each system role
- **Permissions Scope**: Detailed breakdown of capabilities
- **Vessel Access**: Shows current vessel relationships

### Action Buttons
- **View**: Navigate to staff details page
- **Edit**: Edit staff member information
- **Manage Permissions**: Manage access levels (placeholder)
- **Suspend Access**: Suspend staff access (placeholder, superadmins excluded)

### Statistics Cards
- **Total Staff**: Count of all staff members
- **Super Admins**: Count of super administrators
- **Staff Members**: Count of regular staff members
- **Developers**: Count of developers
- **With Vessel Access**: Staff members who have vessel access

## Technical Implementation

### Controller
- **File**: `app/Http\Controllers\Admin\StaffManagementController.php`
- **Methods**: `index()`, `show()`, `edit()`, `update()`
- **Features**: Role restriction, eager loading, relationship counting, advanced filtering

### Views
- **Index**: `resources/views/admin/staff/index.blade.php`
- **Show**: `resources/views/admin/staff/show.blade.php`
- **Edit**: `resources/views/admin/staff/edit.blade.php`
- **Layout**: Extends `layouts/admin.blade.php`

### Routes
- **Index**: `GET /admin/staff` → `admin.staff.index`
- **Show**: `GET /admin/staff/{user}` → `admin.staff.show`
- **Edit**: `GET /admin/staff/{user}/edit` → `admin.staff.edit`
- **Update**: `PUT /admin/staff/{user}` → `admin.staff.update`

### Security Features
- **Role Validation**: Ensures only staff members can be accessed
- **Admin Middleware**: All routes protected by `AdminOnly` middleware
- **Input Validation**: Form validation for all user inputs

## Permissions Scope Details

### Super Administrator Capabilities
- Full system access and configuration
- User and staff management
- Data management and analytics
- System security and policies
- Audit logging and monitoring

### Staff Member Capabilities
- Vessel and crew management
- Maintenance and operational tools
- Reports and business intelligence
- Limited user management
- Customer support tools

### Developer Capabilities
- System development and testing
- API access and debugging
- Technical monitoring and metrics
- Data access for development
- System architecture tools

## Usage Examples

### Filtering Staff by Role
1. Navigate to Staff Management
2. Select specific role from System Role dropdown
3. Click "Apply Filters"
4. View filtered results

### Viewing Staff Details
1. Find staff member in the table
2. Click the eye icon (View button)
3. Review comprehensive information and permissions
4. Use back button to return to staff list

### Editing Staff Member
1. Find staff member in the table
2. Click the edit icon (Edit button)
3. Modify information as needed
4. Click "Update Staff Member" to save

### Managing Permissions
1. Navigate to staff details page
2. Click "Manage Permissions" button
3. Adjust access levels and capabilities
4. Save changes (future enhancement)

## Future Enhancements

### Planned Features
- Permission management interface
- Role-based access control (RBAC)
- Staff activity logging and monitoring
- Bulk staff operations
- Staff invitation system
- Audit trail for staff changes

### Technical Improvements
- Real-time permission updates
- Advanced role customization
- Staff performance metrics
- Integration with external auth systems
- API endpoints for staff management

## Security Considerations

- **Role-Based Access**: Different capabilities based on system role
- **Admin Middleware**: All routes protected by admin authentication
- **Input Validation**: Comprehensive form validation and sanitization
- **Audit Logging**: All staff changes should be logged (future enhancement)
- **Permission Inheritance**: Clear hierarchy of access levels

## Troubleshooting

### Common Issues
- **No Staff Displayed**: Check database connection and user table
- **Filter Not Working**: Verify filter parameters and database relationships
- **Permission Errors**: Ensure user has appropriate admin access
- **Edit Not Working**: Check form validation and database constraints

### Performance Tips
- **Eager Loading**: Relationships are properly eager loaded
- **Indexing**: Ensure proper database indexes on frequently filtered fields
- **Caching**: Consider implementing caching for staff statistics
- **Pagination**: Results are paginated for better performance

## Integration with User Management

The Staff Management system works alongside the User Management system:

- **Staff Management**: Handles superadmin, staff, and dev users
- **User Management**: Handles regular users (system_role: 'user')
- **Clear Separation**: No overlap between the two systems
- **Consistent Interface**: Both use similar design patterns and components
- **Shared Infrastructure**: Both leverage the same database relationships and pagination

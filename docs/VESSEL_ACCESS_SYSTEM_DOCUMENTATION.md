# Vessel Access System Documentation

## Overview

The Vessel Access System is a comprehensive access control mechanism that ensures all application functionality is properly scoped to vessels while providing system administrators with the ability to access any vessel without creating individual boarding records.

**Status: ✅ FULLY IMPLEMENTED AND WORKING**

## System Architecture

### Core Components

1. **User Model Methods**
   - `hasSystemAccessToVessel(Vessel $vessel): bool` - Checks if user can access a specific vessel
   - `getAccessibleVessels()` - Returns all vessels the user can access

2. **VesselAccess Middleware**
   - Route-level protection for all vessel-scoped operations
   - Automatically extracts vessel ID from various route parameter types
   - Applies vessel access validation before controller execution

3. **Helper Functions**
   - `currentVessel()` - Returns the currently active vessel for the user
   - `currentUserBoarding()` - Returns the current user's boarding record

4. **Controller-Level Security**
   - Additional vessel access checks in all relevant controllers
   - Consistent authorization patterns across the application

### Security Model

- **System Users** (`superadmin`, `staff`, `dev`): Automatic access to all vessels
- **Regular Users**: Access only to vessels with active boarding records
- **Route Protection**: Middleware validation on all sensitive routes
- **Controller Security**: Additional checks at the business logic level

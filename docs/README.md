# DeckCheck Documentation

Welcome to the DeckCheck application documentation. This repository contains comprehensive documentation for the vessel management and maintenance tracking system.

## 📚 Documentation Index

### **Core System Documentation**
- **[Vessel Access System Documentation](VESSEL_ACCESS_SYSTEM_DOCUMENTATION.md)** - Complete guide to the vessel access control system
- **[Vessel Access Quick Reference](VESSEL_ACCESS_QUICK_REFERENCE.md)** - Quick reference for developers and administrators

### **User Management Documentation**
- **[User Management Guide](USER_MANAGEMENT_README.md)** - User roles, permissions, and management
- **[Staff Management Guide](STAFF_MANAGEMENT_README.md)** - Staff and admin user management

### **System Architecture**
- **Vessel Access Control** - Implemented and working
- **System Role Management** - `superadmin`, `staff`, `dev`, `user` roles
- **Vessel Scoping** - All functionality properly scoped to vessels
- **Middleware Protection** - `vessel.access` middleware on all sensitive routes

## 🚀 Current Status

### **✅ Completed Features**
- **Vessel Access System** - Fully implemented and working
- **System Admin Access** - System users can access any vessel without boarding records
- **Regular User Access** - Users access only vessels they're boarded on
- **Route Protection** - All sensitive routes protected by vessel access middleware
- **Controller Security** - Vessel access checks in all relevant controllers

### **🔒 Security Model**
- **System Users** (`superadmin`, `staff`, `dev`): Access to all vessels
- **Regular Users**: Access only to vessels with active boarding records
- **Middleware Protection**: Route-level vessel access validation
- **Controller Checks**: Additional security at the controller level

## 📋 Quick Start

1. **System Users**: Can access any vessel via admin interface or vessel switching
2. **Regular Users**: Access vessels through their boarding records
3. **Vessel Switching**: Use the user modal in the sidebar to switch between accessible vessels

## 🔧 Technical Details

- **Middleware**: `app/Http/Middleware/VesselAccess.php`
- **User Model**: `hasSystemAccessToVessel()` method
- **Route Protection**: `vessel.access` middleware on sensitive routes
- **Helper Functions**: `currentVessel()` and `currentUserBoarding()` in `app/helpers.php`

## 📝 Recent Updates

See [CHANGELOG.md](CHANGELOG.md) for detailed information about recent changes and updates to the system.

---

*Last updated: August 2025*

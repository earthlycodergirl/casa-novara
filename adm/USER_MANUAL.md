# Casa Novara Admin System - User Manual

## Table of Contents
1. [Getting Started](#getting-started)
2. [Dashboard Overview](#dashboard-overview)
3. [Property Management](#property-management)
4. [Content Management](#content-management)
5. [System Administration](#system-administration)
6. [Troubleshooting](#troubleshooting)

---

## Getting Started

### Login Process
1. Navigate to the admin panel URL
2. Enter your username and password
3. Click "Login" to access the system
4. If you forget your password, contact technical support

### First Time Setup
- Ensure you have proper admin credentials
- Verify your user account is active
- Contact technical support if you cannot access the system

---

## Dashboard Overview

The Casa Novara admin system is organized into three main sections:

### 🏠 Casa Novara Website
- **Listings** - Manage property listings
- **Inquiries** - View and manage customer inquiries
- **Blog** - Create and manage blog posts
- **Testimonials** - Manage customer testimonials

### 📋 Content Manager
- **Property Locations** - Manage geographical locations
- **Pricing Types** - Define pricing categories
- **Property Types** - Manage property categories
- **Property Sub Types** - Define property subcategories
- **Listing Types** - Configure listing categories
- **Property Zones** - Manage geographical zones

### ⚙️ Administration
- **Users** - Manage admin users and permissions
- **Contact Info** - Update contact information
- **Settings** - System configuration

---

## Property Management

### Managing Listings

#### Adding a New Property Listing
1. Navigate to **Listings** from the main menu
2. Click **"Add New Listing"** button
3. Fill in required information:
   - **Property Title** - Clear, descriptive name
   - **Property Type** - Select from predefined types
   - **Property Sub Type** - More specific categorization
   - **Location** - Choose from location hierarchy
   - **Pricing Type** - Sale, rent, etc.
   - **Price** - Property value
   - **Description** - Detailed property description
   - **Features** - Property amenities and features
   - **Images** - Upload property photos

#### Editing Existing Listings
1. Go to **Listings** page
2. Find the property you want to edit
3. Click the **"Edit"** button next to the listing
4. Make your changes
5. Click **"Save Changes"**

#### Deleting Listings
1. Navigate to **Listings**
2. Find the property to delete
3. Click the **"Delete"** button
4. Confirm deletion when prompted

**⚠️ Warning:** Deleted listings cannot be recovered!

#### Bulk Operations
- **Select Multiple:** Use checkboxes to select multiple listings
- **Bulk Delete:** Select properties and use "Delete Selected" option

### Managing Property Images
1. **Upload Images:** Use the image upload section in listing editor
2. **Image Requirements:**
   - Recommended size: 1200x800 pixels
   - Formats: JPG, PNG, GIF
   - Maximum file size: 5MB per image
3. **Set Featured Image:** First uploaded image becomes the featured image
4. **Reorder Images:** Drag and drop to reorder gallery images

---

## Content Management

### Property Locations

The location system uses a hierarchical structure: **State → City → Town → Area**

#### Adding Locations

**Adding a New City:**
1. Go to **Property Locations**
2. Select the state (default: your region)
3. Click **"Add New City"**
4. Enter city name in English and Spanish (if applicable)
5. Click **"Save"**

**Adding a New Town:**
1. Navigate to **Property Locations**
2. Select the state, then select the city
3. Click **"Add New Town"**
4. Enter town name
5. Click **"Save"**

**Adding a New Area:**
1. Go to **Property Locations**
2. Navigate: State → City → Town
3. Click **"Add New Area"**
4. Enter area name
5. Click **"Save"**

#### Editing Locations
1. Navigate to the location level you want to edit
2. Find the location in the list
3. Click **"Edit"** next to the location name
4. Make changes and click **"Update"**

#### Deleting Locations
1. Find the location you want to delete
2. Click **"Delete"** next to the location
3. Confirm deletion

**⚠️ Warning:** You cannot delete locations that are being used by existing properties!

### Property Types

Property types categorize listings (e.g., House, Apartment, Commercial, Land).

#### Adding Property Types
1. Go to **Property Types**
2. Click **"Add New Property Type"**
3. Enter type name in English
4. Enter type name in Spanish (optional)
5. Click **"Add Property Type"**

#### Managing Property Types
- **Edit:** Click edit button next to any type
- **Delete:** Click delete button (only if not in use)
- **Reorder:** Types are displayed in creation order

### Property Sub Types

Sub types provide more specific categorization within property types.

#### Adding Sub Types
1. Navigate to **Property Sub Types**
2. Select the parent **Property Type**
3. Click **"Add New Sub Type"**
4. Enter sub type name
5. Choose parent property type
6. Click **"Save"**

### Pricing Types

Define how properties are priced (Sale, Rent, Lease, etc.).

#### Managing Pricing Types
1. Go to **Pricing Types**
2. Add new pricing categories as needed
3. Edit existing types by clicking the edit button
4. Delete unused pricing types

### Listing Types

Categories for different kinds of listings (Featured, Premium, Standard, etc.).

#### Adding Listing Types
1. Navigate to **Listing Types**
2. Click **"Add New Type"**
3. Enter type name and description
4. Set any special properties or display options
5. Save changes

### Property Zones

Geographical zones for better property organization.

#### Managing Zones
1. Go to **Property Zones**
2. Add zones based on your market areas
3. Assign properties to appropriate zones
4. Use zones for filtering and organization

---

## System Administration

### User Management

#### Adding New Admin Users
1. Navigate to **Users**
2. Click **"Add New User"**
3. Fill in user information:
   - **Username** - Login username (unique)
   - **Password** - Strong password (minimum 6 characters)
   - **Full Name** - User's real name
   - **Email** - Valid email address
   - **Position** - User's role/title
   - **Status** - Active/Inactive
4. Click **"Add User"**

#### Managing Existing Users
- **Edit User:** Click edit button to modify user details
- **Deactivate User:** Temporarily disable user access
- **Activate User:** Re-enable disabled user
- **Delete User:** Permanently remove user (use carefully!)

#### User Permissions
- All admin users have full access to the system
- Contact technical support for role-based permissions

### Managing Inquiries

#### Viewing Customer Inquiries
1. Go to **Inquiries**
2. View list of all customer inquiries
3. Information displayed:
   - Customer name and contact details
   - Property of interest
   - Message/inquiry details
   - Date submitted
   - Contact information

#### Processing Inquiries
1. Review inquiry details
2. Contact customer using provided information
3. Mark inquiry as processed (if system supports it)
4. Delete inquiry when no longer needed

### Blog Management

#### Creating Blog Posts
1. Navigate to **Blog**
2. Click **"Add New Post"**
3. Enter post details:
   - **Title** - Blog post headline
   - **Content** - Blog post body
   - **Featured Image** - Main post image
   - **Categories** - Post categories
   - **Tags** - Post tags for SEO
4. Preview and publish

#### Managing Blog Posts
- **Edit Posts:** Update content, images, or categories
- **Delete Posts:** Remove outdated content
- **Schedule Posts:** Set future publication dates

### Testimonials

#### Adding Customer Testimonials
1. Go to **Testimonials**
2. Click **"Add New Testimonial"**
3. Enter testimonial information:
   - **Customer Name** - Client's name
   - **Testimonial Text** - Customer feedback
   - **Customer Photo** - Optional profile image
   - **Property/Service** - What they're reviewing
   - **Rating** - Star rating (if applicable)
4. Save testimonial

#### Managing Testimonials
- **Edit:** Update testimonial content
- **Feature:** Mark testimonials as featured
- **Delete:** Remove inappropriate or outdated testimonials

---

## Best Practices

### Property Listings
1. **High-Quality Images:** Always use professional, well-lit photos
2. **Detailed Descriptions:** Include all relevant property details
3. **Accurate Information:** Double-check all property details
4. **Regular Updates:** Keep listings current and remove sold properties
5. **SEO-Friendly Titles:** Use descriptive, searchable property titles

### Content Organization
1. **Consistent Naming:** Use consistent naming conventions
2. **Logical Hierarchy:** Organize locations in logical order
3. **Regular Cleanup:** Remove unused categories and locations
4. **Backup Important Data:** Regularly backup your property data

### User Management
1. **Strong Passwords:** Enforce strong password policies
2. **Regular Access Review:** Periodically review user access
3. **Remove Inactive Users:** Deactivate users who no longer need access
4. **Track Changes:** Monitor who makes what changes

---

## Troubleshooting

### Common Issues

#### Cannot Login
- **Check credentials:** Verify username and password
- **Account status:** Ensure account is active
- **Contact admin:** Request password reset if needed

#### Images Not Uploading
- **File size:** Ensure images are under 5MB
- **File format:** Use JPG, PNG, or GIF only
- **Internet connection:** Check your connection
- **Browser cache:** Clear browser cache and try again

#### Changes Not Saving
- **Form validation:** Check for required fields
- **Session timeout:** You may need to login again
- **Browser issues:** Try a different browser
- **JavaScript errors:** Check browser console for errors

#### Slow Performance
- **Clear cache:** Clear browser cache and cookies
- **Close tabs:** Close unnecessary browser tabs
- **Check connection:** Verify internet connection speed
- **Contact support:** Report persistent performance issues

### Getting Help

#### Technical Support
- **Email:** info@grfreedom.com
- **Response Time:** Within 24 hours during business days
- **Include Details:** Provide screenshots and error messages

#### Training Resources
- **User Manual:** This comprehensive guide
- **Video Tutorials:** Contact support for training videos
- **One-on-One Training:** Available upon request

---

## Security Guidelines

### Password Security
- Use strong passwords with mixed characters
- Change passwords regularly
- Don't share login credentials
- Log out when finished

### Data Protection
- Regularly backup important data
- Don't access admin panel on public computers
- Use secure internet connections
- Keep browser updated

### Content Guidelines
- Verify all property information before publishing
- Use only authorized images
- Respect customer privacy
- Follow fair housing guidelines

---

*This manual is updated regularly. For the latest version or additional support, contact technical support.*

**Last Updated:** October 2025  
**Version:** 1.0
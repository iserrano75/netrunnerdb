# User Collection Feature

## Overview
This feature allows users to track their Netrunner card collection by marking which packs they own.

## Components

### Database
- **Table**: `user_collection`
- **Purpose**: Stores many-to-many relationship between users and packs
- **Migration**: See `app/DoctrineMigrations/user_collection_migration.sql`

### Entities
- **UserCollection** (`src/AppBundle/Entity/UserCollection.php`)
  - Represents a user's ownership of a pack
  - Fields: id, user, pack, dateAdded

### Controllers
- **CollectionController** (`src/AppBundle/Controller/CollectionController.php`)
  - `indexAction`: Displays the collection management page
  - `togglePackAction`: Handles adding/removing packs from collection via AJAX

### Routes
- `/{locale}/user/collection` (GET) - View collection page
- `/user/collection/toggle` (POST) - Toggle pack ownership

### Views
- **index.html.twig** (`app/Resources/views/Collection/index.html.twig`)
  - Displays all packs grouped by cycle
  - Interactive checkboxes to mark owned packs
  - Real-time updates via AJAX

### Navigation
- Added "My Collection" link to main navigation menu

## Usage

### For Users
1. Navigate to "My Collection" from the main menu
2. Click on any pack to toggle ownership
3. Owned packs are highlighted in green with a checkmark
4. Changes are saved immediately

### For Developers

#### To apply the database migration:
```bash
php bin/console doctrine:schema:update --force
```

Or manually apply the SQL from `app/DoctrineMigrations/user_collection_migration.sql`

#### To verify the installation:
1. Ensure the UserCollection entity is recognized by Doctrine
2. Check that the collection routes are registered
3. Verify the navigation link appears when logged in

## Technical Details

### Security
- All collection actions require authentication (`@IsGranted("IS_AUTHENTICATED_REMEMBERED")`)
- Pack IDs are validated before processing
- CASCADE delete ensures orphaned records are cleaned up

### Database
- Unique constraint prevents duplicate entries (user_id, pack_id)
- Indexed for performance
- Foreign key constraints maintain referential integrity

### Frontend
- Uses jQuery for AJAX requests
- Bootstrap styling for consistent UI
- Real-time visual feedback on pack selection
- Error handling with user-friendly messages

## Future Enhancements
Potential improvements that could be added:
- Export collection list
- Filter/search packs
- Collection statistics (percentage owned, etc.)
- Share collection with other users
- Integration with deck builder (show missing cards)

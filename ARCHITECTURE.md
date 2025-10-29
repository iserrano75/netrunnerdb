# User Collection Feature - Architecture Diagram

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        PRESENTATION LAYER                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  Navigation Menu (layout.html.twig)                    │    │
│  │  ┌─────────┐  ┌──────────────┐                         │    │
│  │  │My Decks │  │My Collection │  [Other menu items]     │    │
│  │  └─────────┘  └──────────────┘                         │    │
│  └─────────────────────┬────────────────────────────────────┘    │
│                        │ Click                                   │
│                        ▼                                         │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Collection View (Collection/index.html.twig)          │   │
│  │                                                          │   │
│  │  ┌─ Core Set ──────────────────────────────────┐       │   │
│  │  │ ☑ Core Set    ☐ What Lies Ahead  ☑ Trace   │       │   │
│  │  └──────────────────────────────────────────────┘       │   │
│  │  ┌─ Genesis Cycle ─────────────────────────────┐       │   │
│  │  │ ☐ Pack 1      ☑ Pack 2           ☐ Pack 3  │       │   │
│  │  └──────────────────────────────────────────────┘       │   │
│  │                                                          │   │
│  │  JavaScript/AJAX Handler                                │   │
│  └──────────────────────┬───────────────────────────────────┘   │
│                         │ POST /user/collection/toggle         │
└─────────────────────────┼──────────────────────────────────────┘
                          │
┌─────────────────────────┼──────────────────────────────────────┐
│                         ▼         CONTROLLER LAYER              │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  CollectionController.php                               │   │
│  │                                                          │   │
│  │  ┌───────────────────┐  ┌────────────────────────────┐ │   │
│  │  │ indexAction()     │  │ togglePackAction()         │ │   │
│  │  │                   │  │                            │ │   │
│  │  │ - Get user        │  │ - Validate pack_id         │ │   │
│  │  │ - Load cycles     │  │ - Find existing ownership  │ │   │
│  │  │ - Load user       │  │ - Add/Remove collection    │ │   │
│  │  │   collections     │  │ - Return JSON response     │ │   │
│  │  │ - Render view     │  │                            │ │   │
│  │  └──────┬────────────┘  └──────────┬─────────────────┘ │   │
│  └─────────┼────────────────────────────┼───────────────────┘   │
│            │                            │                       │
└────────────┼────────────────────────────┼───────────────────────┘
             │                            │
┌────────────┼────────────────────────────┼───────────────────────┐
│            ▼                            ▼    REPOSITORY LAYER    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  UserCollectionRepository.php                          │    │
│  │                                                         │    │
│  │  ┌─────────────────────┐  ┌──────────────────────────┐│    │
│  │  │ findByUser()        │  │ userOwnsPack()           ││    │
│  │  │                     │  │                          ││    │
│  │  │ - Query collections │  │ - Check ownership        ││    │
│  │  │ - Order by date     │  │ - Return boolean         ││    │
│  │  └─────────────────────┘  └──────────────────────────┘│    │
│  └───────────────────────────────┬─────────────────────────┘    │
│                                  │                              │
└──────────────────────────────────┼──────────────────────────────┘
                                   │
┌──────────────────────────────────┼──────────────────────────────┐
│                                  ▼        ENTITY LAYER           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐      ┌──────────────────┐      ┌──────────┐ │
│  │   User       │      │ UserCollection   │      │   Pack   │ │
│  ├──────────────┤      ├──────────────────┤      ├──────────┤ │
│  │ id           │◄────┐│ id               │┌────►│ id       │ │
│  │ username     │     ││ user_id          ││     │ code     │ │
│  │ email        │     ││ pack_id          ││     │ name     │ │
│  │ ...          │     ││ date_added       ││     │ ...      │ │
│  │ collections  │◄────┘│                  │└────►│          │ │
│  └──────────────┘      └──────────────────┘      └──────────┘ │
│         │                                                │      │
└─────────┼────────────────────────────────────────────────┼──────┘
          │                                                │
┌─────────┼────────────────────────────────────────────────┼──────┐
│         ▼                 DATABASE LAYER                 ▼      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────┐         ┌──────────────────┐        ┌──────────┐│
│  │  user    │         │ user_collection  │        │   pack   ││
│  ├──────────┤         ├──────────────────┤        ├──────────┤│
│  │ id (PK)  │◄───────┐│ id (PK)          │┌──────►│ id (PK)  ││
│  │ username │        ││ user_id (FK)     ││       │ code     ││
│  │ email    │        ││ pack_id (FK)     ││       │ name     ││
│  │ ...      │        ││ date_added       ││       │ ...      ││
│  └──────────┘        │└──────────────────┘│       └──────────┘│
│                      │  UNIQUE(user,pack) │                   ││
│                      │  INDEX(user,pack)  │                   ││
│                      │  CASCADE DELETE    │                   ││
│                      └────────────────────┘                    │
└─────────────────────────────────────────────────────────────────┘

## Data Flow

### View Collection Page
1. User clicks "My Collection" in navigation
2. Browser sends GET request to /{locale}/user/collection
3. CollectionController::indexAction() is called
4. Controller fetches:
   - All cycles (with packs)
   - User's collection entries
5. Renders template with data
6. Browser displays packs grouped by cycle

### Toggle Pack Ownership
1. User clicks on a pack
2. JavaScript captures click event
3. AJAX POST request to /user/collection/toggle with pack_id
4. CollectionController::togglePackAction() is called
5. Controller:
   - Validates pack_id
   - Checks if UserCollection entry exists
   - If exists: DELETE (remove from collection)
   - If not: INSERT (add to collection)
6. Returns JSON: {success: true, owned: true/false}
7. JavaScript updates UI:
   - Changes border color
   - Updates checkbox icon
   - Shows success message

## Security Flow

```
Request → Authentication Check → Authorization → Validation → Action
             ↓ Not logged in        ↓ Not owner    ↓ Invalid
          Redirect to login      403 Forbidden    400 Bad Request
```

## Performance Optimizations

1. **EXTRA_LAZY Loading**: Collections only loaded when accessed
2. **Indexed Queries**: Fast lookup via (user_id, pack_id) index
3. **UNIQUE Constraint**: Database-level duplicate prevention
4. **AJAX Updates**: No full page reload
5. **Minimal DOM**: Only affected elements updated

## Error Handling

```
┌─────────────────┐
│  User Action    │
└────────┬────────┘
         │
         ▼
┌─────────────────────┐
│ Validation Layer    │
├─────────────────────┤
│ - Pack ID exists?   │
│ - User logged in?   │
│ - Valid request?    │
└────────┬────────────┘
         │ Valid ✓
         ▼
┌─────────────────────┐      ┌──────────────────┐
│ Database Operation  │─────►│ Error Occurred?  │
└─────────────────────┘      └────────┬─────────┘
                                      │ Yes
                                      ▼
                             ┌────────────────────┐
                             │ Return Error JSON  │
                             │ + User Message     │
                             └────────────────────┘
```

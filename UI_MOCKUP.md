# User Collection UI Mockup

## Page Layout

### Header
```
NetrunnerDB Navigation Bar
[Logo] [My Decks] [My Collection] [Decklists ▼] [Sets] [Factions ▼] ...
```

### Main Content

```
╔═══════════════════════════════════════════════════════════╗
║                    My Collection                           ║
╠═══════════════════════════════════════════════════════════╣
║ Mark the packs you own to keep track of your collection.  ║
║ Click on a pack to toggle ownership.                       ║
║                                                            ║
║ ┌─ Cycle Name: Core Set ───────────────────────────────┐ ║
║ │                                                        │ ║
║ │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐│ ║
║ │  │ ✓ Core Set   │  │ □ What Lies  │  │ ✓ Trace      ││ ║
║ │  │              │  │   Ahead      │  │   Amount     ││ ║
║ │  │ Released:    │  │              │  │              ││ ║
║ │  │ 2012-08-21   │  │ Released:    │  │ Released:    ││ ║
║ │  └──────────────┘  │ 2012-09-15   │  │ 2012-10-10   ││ ║
║ │                    └──────────────┘  └──────────────┘│ ║
║ └────────────────────────────────────────────────────────┘ ║
║                                                            ║
║ ┌─ Cycle Name: Genesis Cycle ──────────────────────────┐ ║
║ │                                                        │ ║
║ │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐│ ║
║ │  │ ✓ What Lies  │  │ □ Trace      │  │ □ Cyber      ││ ║
║ │  │   Ahead      │  │   Amount     │  │   Exodus     ││ ║
║ │  │              │  │              │  │              ││ ║
║ │  │ Released:    │  │ Released:    │  │ Released:    ││ ║
║ │  │ 2013-01-15   │  │ 2013-02-20   │  │ 2013-03-18   ││ ║
║ │  └──────────────┘  └──────────────┘  └──────────────┘│ ║
║ └────────────────────────────────────────────────────────┘ ║
╚═══════════════════════════════════════════════════════════╝
```

## Visual Indicators

### Owned Pack (Green border)
```
┌──────────────────────────────────┐
│ ✓ Core Set                       │  ← Checkmark in green
│                                  │
│ Released: 2012-08-21             │
└──────────────────────────────────┘
Border: Green (#5cb85c)
Background: Light green (#f0fff0)
```

### Unowned Pack (Gray border)
```
┌──────────────────────────────────┐
│ □ What Lies Ahead                │  ← Checkbox in gray
│                                  │
│ Released: 2012-09-15             │
└──────────────────────────────────┘
Border: Light gray (#ddd)
Background: White (#fff)
```

## Interactions

1. **Click Pack**: Toggle ownership (checkbox changes, colors update)
2. **Hover Pack**: Cursor changes to pointer
3. **After Toggle**: Brief success/info message appears at bottom
4. **AJAX Request**: Pack ownership saved immediately to database

## Responsive Design

- Desktop (lg): 4 packs per row (col-lg-3)
- Tablet (md): 3 packs per row (col-md-4)
- Mobile (sm): 2 packs per row (col-sm-6)

## Success Message
```
┌────────────────────────────────────────┐
│ ✓ Pack added to your collection!      │  (Green alert, fades after 3s)
└────────────────────────────────────────┘
```

## Error Message
```
┌────────────────────────────────────────┐
│ ✗ An error occurred. Please try again.│  (Red alert, fades after 3s)
└────────────────────────────────────────┘
```

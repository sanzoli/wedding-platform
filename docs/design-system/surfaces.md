# Surfaces — admin and guest

This document defines the surface partition of the wedding-platform
design system. It is the canonical reference for where styles,
tokens, components, and composables live.

## 1. What is a surface

A surface is a **layer of style, not of behavior**. It defines the
visual identity for a category of pages — admin (data management) or
guest (editorial / ceremonial). Two pages on different surfaces may
share the same underlying behavior (a button is still a button) but
look and feel different because they consume different tokens and
utility classes.

## 2. The three layers

| Layer | Purpose | Lives in | Prefix |
|---|---|---|---|
| **Shared base** | Lowest common denominator — primitive palette (`--p-*`), semantic tokens (`--background`, `--foreground`, `--primary`, etc.), base typography (`.type-display`, `.type-heading`, `.type-body`), the global `cursor: pointer` rule. | `resources/css/tokens.css` + `resources/css/app.css` | (none) |
| **Admin Surface** | Data-management tokens and utilities — typography for labels/data/actions, badge importance palette, FAB sizing, edit-flash animation, table-header typography. | `resources/css/admin-surface.css` | `admin-*` |
| **Guest Surface** | Editorial / ceremonial tokens and utilities — display typography, eyebrows, hero sections, gallery aspect ratios, scroll-spy, viewport-triggered motion. | `resources/css/guest-surface.css` | `guest-*` |

## 3. Partition rule

Shared is the **lowest common denominator**. When unsure, push to a
surface. Demoting from shared to a surface is expensive (touches every
file that consumed the shared name); promoting from a surface to shared
is cheap.

- No prefix → shared.
- `admin-*` prefix → admin surface.
- `guest-*` prefix → guest surface.

**Grep is the verifier.** A grep for `admin-` or `guest-` across
`resources/` and `app/` must always agree with the file location of
each match, with two expected exceptions: the `@import` statements in
`resources/css/app.css` that wire the surface files into the bundle,
and references inside this document.

## 4. Class versus file

The decision to fork a component into a surface-specific file is
about **API divergence**, not visual divergence:

- **Same API, different appearance across surfaces** → keep one file in
  `components/`, use different wrapping classes. The component does
  not know which surface is consuming it.
- **API diverges between surfaces** (different props, slots, events)
  → fork into `components/admin/Component.vue` or
  `components/guest/Component.vue`.

Moving a file to `components/<surface>/` is justified when the props,
slots, or events of the component carry surface-specific concepts —
not merely because the styles differ.

## 5. When to create a file under `components/admin/`

A component belongs in `components/admin/` when it assumes admin
patterns:

- Table with sortable columns and sticky toolbar.
- Mobile FAB with safe-area clearance.
- Dense forms.
- Search-as-filter inputs.
- Importance badges (high / must-have / normal / low).
- Inline-editable rows.

Its props carry admin concepts: `search`, `sortOptions`, `sortable`,
`inline-edit`, `importance`, named slots like `toolbar` / `header` /
`body`.

A few components and composables live under `admin/` despite a
generic-looking API — `SearchBar.vue`, `useAlert.ts` — because their
visual density and typical usage (table filtering, destroy-confirmation
modals) is admin-shaped, even if their props could in theory be reused.
If a guest page ever needs the same primitive, promote it back to
shared rather than importing across surfaces.

## 6. When to create a file under `components/guest/`

A component belongs in `components/guest/` when it assumes editorial
patterns:

- Full-bleed hero.
- Gallery with fixed aspect ratios.
- Sections composed of eyebrow + title + body.
- Scroll-spy navigation.
- Viewport-triggered animations.
- Reading-width constraints (60–75ch).

Its props carry guest concepts: `image`, `caption`, `eyebrow`, `align`,
`parallax`, `tone`.

## 7. When to leave a file under `components/` (shared)

Primitives and generic patterns whose API fits any surface stay
shared: `Heading`, `Card`, `Button`, `Dialog`, `Icon`, `Avatar`. Style
adjusts via wrapper classes chosen by the consumer.

## 8. When to add a rule to `admin-surface.css`

Add to `admin-surface.css` when the concept is admin-shaped:

- Importance levels.
- Edit state visualization (flash, focus highlight).
- Tabular density.
- FAB clearance.
- Table-header typography.

Name the token or utility with the `admin-` prefix.

## 9. When to add a rule to `guest-surface.css`

Add to `guest-surface.css` when the concept is editorial:

- Eyebrow / overline typography.
- Hero display sizing.
- Section vertical spacing.
- Image aspect ratios.
- Ceremonial motion (entrance animations, parallax).

Name the token or utility with the `guest-` prefix.

## 10. When to add a rule to shared

Add to shared (`tokens.css` or `app.css`) when the concept is
agnostic across surfaces:

- Semantic palette (`--primary`, `--secondary`, `--destructive`).
- Body typography.
- Base radii and shadows.
- Cursor behavior for interactive elements.

No prefix.

## 11. Naming convention

- **Tokens**: `--<surface>-<concept>-<role>`. Examples:
  - `--admin-badge-high-bg`
  - `--admin-fab-size`
  - `--guest-font-display`
- **Utilities**: `.<surface>-<concept>-<variant>`. Examples:
  - `.admin-badge-high-importance`
  - `.admin-fab-safe-area`
- **Components**: `components/<surface>/Component.vue`. Do **not**
  prefix the filename itself (no `AdminTable.vue`). The folder is the
  prefix.

## 12. Partition verification

After any change touching tokens or utilities, run the following
greps across `resources/` and `app/` to confirm partition integrity:

```bash
# No bare (unprefixed) admin-shaped class names should exist:
grep -rn "\.type-label\b\|\.type-data\b\|\.type-action\b\|\.type-table-header\b" resources/ app/
grep -rn "\.badge-\(high\|must-have\|normal\|low\)-importance\b" resources/ app/
grep -rn "\.animate-flash-edit\b\|\.fab-safe-area\b" resources/ app/

# No bare admin-shaped tokens:
grep -rn "\-\-fab-size\b\|\-\-font-label\b\|\-\-font-data\b\|\-\-font-action\b\|\-\-badge-" resources/ app/
```

All checks should return zero matches.

Cross-surface imports leak through paths: any `@/components/admin/*`
import inside a guest page (or vice versa) is a fault, detectable by
grep with no extra tooling needed.

## 13. Deferred work

Prior work that does not live on `main` has been preserved in
`recover/*` tags and indexed in the isolated `recovery/index` branch.
For details, contact the engineer responsible. This document
intentionally does not enumerate the contents — surface conventions
are about the general rule, not the historical exceptions.

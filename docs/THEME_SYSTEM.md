# Global Theme System

This document explains how to use the global theme system implemented in the application.

## Primary Color

The primary color is set to `#B8EC27` (bright lime green) and is used throughout the application for:

- Active tab states
- Button highlights
- Border accents
- Background highlights

## Color Variations

The theme system provides multiple variations of the primary color:

### Primary Colors
- `primary-50` to `primary-950` - Full color scale from lightest to darkest
- `primary-500` - Main primary color (`#B8EC27`)

### Accent Colors
- `accent-50` to `accent-950` - Same as primary colors for consistency
- `accent-500` - Main accent color (same as primary)

## Usage in Tailwind Classes

You can use these colors in your Blade templates with standard Tailwind classes:

```html
<!-- Background colors -->
<div class="bg-primary-500">Primary background</div>
<div class="bg-primary-200">Light primary background</div>

<!-- Text colors -->
<span class="text-primary-700">Primary text</span>

<!-- Border colors -->
<div class="border border-primary-300">Primary border</div>

<!-- Hover states -->
<button class="hover:bg-primary-100">Hover button</button>
```

## CSS Custom Properties

The theme also provides CSS custom properties for more advanced styling:

```css
:root {
    --color-primary: #B8EC27;
    --color-primary-light: #c5f096;
    --color-primary-lighter: #ddf7c1;
    --color-primary-dark: #8bb018;
    --color-primary-darker: #6f8a1a;
}
```

## Excluded Elements

The following elements are excluded from the dynamic theme system and use global colors:

- Top bar background (`bg-[#F8F8F6]`)
- Avatar outlines (use `border-primary-300` but can be overridden)
- Divider lines (`bg-[#E4E4E4]`)

## Updating the Theme

To change the primary color:

1. Update the color values in `tailwind.config.js`
2. Update the CSS custom properties in `resources/css/app.css`
3. Run `npm run build` to compile the changes

## Examples

### Tab Navigation
```html
<!-- Active tab -->
<button class="bg-primary-200 text-slate-900 border border-primary-300">
    Active Tab
</button>

<!-- Inactive tab -->
<button class="border hover:bg-white">
    Inactive Tab
</button>
```

### Announcement Bar
```html
<div class="border-primary-300 bg-primary-200/40">
    <span class="bg-primary-500"></span>
    Announcement content
</div>
```

### Avatar Borders
```html
<img class="border-2 border-primary-300" src="avatar.jpg" alt="Avatar" />
```

# MFS Accordion Navigation Plugin

## Description
This plugin adds accordion functionality to WordPress navigation blocks for the My Future Self Digital website. It automatically collapses submenu items and expands them based on the current page or user interaction.

## Features
- **Auto-collapse**: All submenus are collapsed by default
- **Smart expansion**: Current page and its ancestors automatically expand
- **Click to toggle**: Click on parent items to expand/collapse submenus
- **Smooth transitions**: CSS animations for a polished user experience
- **Visual indicators**: Arrow icons show expandable items and their state
- **Sibling collapse**: When expanding one section, siblings automatically collapse

## Installation

### On WordPress.com (Business Plan or Higher)
1. Download the plugin ZIP file
2. Go to your WordPress dashboard
3. Navigate to **Plugins** → **Add New** → **Upload Plugin**
4. Click **Choose File** and select `mfs-accordion-navigation.zip`
5. Click **Install Now**
6. Click **Activate Plugin**

### On Self-Hosted WordPress
1. Upload the `mfs-accordion-navigation` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress

## Usage

Once activated, the plugin automatically works with any WordPress Navigation block that has submenu items. No configuration needed!

### How It Works

1. **Top-level items** (Week 1, Week 2, Account, etc.) show by default
2. **Submenu items** are hidden until:
   - You click on the parent item, OR
   - You navigate to a page within that section
3. **Current page ancestry** is always visible
4. **Sibling sections** collapse when you expand a different section

## Customization

### Modifying Styles

Edit `/css/accordion-nav.css` to change:
- Arrow icons (change the `content` property)
- Animation speed (modify `transition` values)
- Colors and spacing
- Indentation levels

### Modifying Behavior

Edit `/js/accordion-nav.js` to change:
- Click behavior
- Animation timing
- Double-click navigation
- Additional features

## File Structure

```
mfs-accordion-navigation/
├── mfs-accordion-navigation.php  # Main plugin file
├── css/
│   └── accordion-nav.css         # Styling
├── js/
│   └── accordion-nav.js          # Accordion logic
└── README.md                     # This file
```

## Browser Compatibility
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Troubleshooting

### Accordion not working?
1. Clear your browser cache
2. Clear WordPress cache (if using a caching plugin)
3. Check browser console for JavaScript errors
4. Ensure your navigation uses the WordPress Navigation block

### Styles not applying?
1. Check that CSS is loading in page source
2. Clear browser cache
3. Try adding `!important` to CSS rules if theme conflicts exist

### Navigation not expanding on current page?
- WordPress should automatically add `.current-menu-item`, `.current-menu-parent`, and `.current-menu-ancestor` classes
- Check your menu settings in **Appearance** → **Menus**

## Future Enhancements

Planned features for future versions:
- Admin settings page
- Customize arrow icons via admin
- Choose animation styles
- Mobile-specific behaviors
- Remember expanded state with localStorage

## Support

For issues, questions, or feature requests related to My Future Self Digital, contact Mark.

## Version History

### 1.0.0 (December 2024)
- Initial release
- Basic accordion functionality
- Auto-expand current page ancestry
- Smooth CSS transitions
- Arrow indicators

## License

GPL v2 or later

---

**Author:** Mark - My Future Self Project
**Website:** https://myfutureself.digital

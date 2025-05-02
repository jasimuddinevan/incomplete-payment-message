# Incomplete Payment Message

A WordPress plugin that displays customizable warning messages for incomplete payments.

![Plugin Banner](assets/banner.png) *← Optional banner image*

## Description

The Incomplete Payment Message plugin allows Developer to display warning messages to WordPress administrators haven't completed their payments. The plugin features a customizable message system with adjustable timing controls and security measures.

## 🔧 Plugin Features

- Full-screen payment reminder with customizable message
- Password-protected settings (Bcrypt secured)
- Forced display with countdown timer (minimum 20 seconds)
- Recurring popup every 2 minutes
- Admin dashboard with card-based UI
- Anti-tampering protection (cannot deactivate when enabled)
- Mobile responsive design


## 🚀 Installation

### Method 1: WordPress Admin
1. Go to **Plugins → Add New**
2. Click **Upload Plugin**
3. Select `incomplete-payment-message.zip`
4. Click **Install Now**
5. Activate the plugin

### Method 2: Manual Upload
1. Unzip the plugin files
2. Upload `/incomplete-payment-message/` folder to `/wp-content/plugins/`
3. Go to **Plugins → Installed Plugins**
4. Activate **Incomplete Payment Message**

## ⚙️ Configuration

1. After activation, go to **Payment Message** in admin menu
2. Default password: `123` (change immediately)
3. Configure:
   - ✔️ Enable/disable message
   - ✏️ Custom message content
   - ⏱️ Popup delay (default 120s)
   - ⏳ Minimum display time (default 20s)

![Admin Panel Screenshot](assets/screenshot-1.png)

## 🔒 Security Notes

- The plugin uses `password_hash()` with Bcrypt
- Settings can only be changed with valid password
- Plugin cannot be deactivated while message is enabled
- No direct database access needed

## ❓ Frequently Asked Questions

### Q: How do I reset the password?
A: You'll need to either:
- Access the database and update the `password_hash` in `wp_options` table
- Temporarily deactivate the plugin via FTP by renaming the folder

### Q: Can I change the popup design?
A: Yes! Edit these files:
- Main CSS: `/assets/css/public.css`
- HTML template: `/includes/public/public-display.php`

### Q: Does this work with caching plugins?
A: Yes, but you may need to exclude the popup JavaScript from caching.

## 🛠️ Developer Notes

- The plugin uses a custom database table for storing settings
- JavaScript is loaded only when message is enabled
- The plugin is fully localized for easy translation


## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher

## Security

- Nonce verification for form submissions
- Password protection for settings changes
- XSS protection through WordPress security functions
- Direct file access prevention

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPL v2 or later.

## Changelog

### 1.0.0
- Initial release
- Basic message functionality
- Admin interface
- Security features

# Incomplete Payment Message Plugin

**Developer:** Jasim Uddin  
**Website:** https://jasimevan.com
**Facebook:** [@jasimuddinevan](https://facebook.com/@jasimuddinevan)  
**Email:** jasimuddin@xmail.net  


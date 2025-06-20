# Troubleshooting Guide

## Common Issues and Solutions

### Module Repository Location
The module should be located in an organized directory structure:
- **Correct**: `/home/phil/repos/extensions/modules/which-police-force`
- **Not**: Directly in the Joomla site

This allows for:
- Clean separation of code from Joomla installations
- Easy testing across multiple Joomla versions
- Version control without site files

### Symlink Setup
1. Module directory symlink:
   ```bash
   sudo -u www-data ln -s /home/phil/repos/extensions/modules/which-police-force /home/phil/sites/[site]/modules/mod_whichpoliceforce
   ```

2. Media directory symlink:
   ```bash
   sudo -u www-data ln -s /home/phil/repos/extensions/modules/which-police-force/media /home/phil/sites/[site]/media/mod_whichpoliceforce
   ```

**Important**: Symlinks must be created as `www-data` user, not root!

### Apache Configuration
Ensure your Apache site configuration includes:
```apache
<Directory /home/phil/sites/[site]>
    Options FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

### Assets Not Loading (CSS/JS)
1. **Web Asset Manager**: Ensure the asset registry file is loaded:
   ```php
   $wa->getRegistry()->addRegistryFile('media/mod_whichpoliceforce/joomla.asset.json');
   ```

2. **Route URLs**: Use `false` parameter to prevent HTML encoding:
   ```php
   Route::_('index.php?option=com_ajax&module=whichpoliceforce&format=json', false);
   ```

### AJAX "Method getAjax does not exist" Error
The helper class name must match Joomla's expectations:
- **Correct**: `class ModWhichPoliceForceHelper`
- **Wrong**: `class ModWhichpoliceforceHelper`

The class name must use proper camelCase with each word capitalized.

### Debugging Tips
1. Check browser console for JavaScript errors
2. Verify symlinks are accessible: `sudo -u www-data ls -la /path/to/symlink`
3. Clear Joomla cache after changes: `sudo rm -rf administrator/cache/* cache/*`
4. Check Apache error logs: `sudo tail -f /var/log/apache2/error.log`

### File Permissions
Ensure all module files are readable by the web server:
```bash
chmod -R 755 /home/phil/repos/extensions/modules/which-police-force
chmod 755 /home/phil/repos /home/phil/repos/extensions /home/phil/repos/extensions/modules
```
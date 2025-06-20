# which-police-force

## Description

A Joomla 5 module that allows users to find which UK police force covers their area by entering a postcode. The module uses the official UK Police API to provide accurate, up-to-date information about local police forces and neighbourhoods.

### Features
- 🔍 **Postcode Lookup**: Enter any UK postcode to find the responsible police force
- 🌐 **Live API Integration**: Uses UK Police API and Postcodes.io for accurate data
- 📍 **Location Details**: Displays area information including district, ward, parish, constituency, region and country
- ⚡ **AJAX Interface**: Smooth user experience without page reloads
- 💾 **Intelligent Caching**: Reduces API calls and improves performance
- 📱 **Responsive Design**: Works perfectly on all devices
- 🎨 **Template-Agnostic Styling**: Minimal CSS that adapts to any Joomla template
- 🌍 **Multilingual Ready**: Full language string support for translations

## Installation

1. Download the module package: `mod_whichpoliceforce_v1.1.0.zip`
2. In Joomla Administrator, go to **System → Install → Extensions**
3. Upload and install the package
4. Go to **Content → Site Modules**
5. Find "Which Police Force?" and configure as needed
6. Assign to desired module position and menu items

## Development Setup

### Quick Start
```bash
# Set up development environment
cd ~/repos/extensions/modules/which-police-force
./scripts/setup-dev.sh

# Access test site
http://example.com/whichpolice-test/
```

### VS Code Remote Development
For the best development experience, use VS Code with Remote-SSH:
- See [docs/VSCODE-SETUP.md](docs/VSCODE-SETUP.md) for detailed instructions
- Edit files on forge directly from your laptop
- Full IntelliSense and Git integration

### Development Scripts
- `scripts/setup-dev.sh` - Set up/refresh development symlink
- `scripts/build.sh` - Create installation package
- `scripts/test-install.sh` - Test clean installation

## Module Configuration

### Basic Settings
- **API URL**: UK Police API endpoint (default provided)
- **Show Title**: Display module title
- **Cache Time**: How long to cache API results (default: 15 minutes)

### Styling
- Module uses minimal, template-agnostic CSS
- Relies on template's Bootstrap/framework classes
- Custom CSS in `media/css/style.css` provides only structural styles
- Responsive design included

## License

This project is licensed under the GNU General Public License version 2 or later.
See the [LICENSE](LICENSE) file for details.

## Project Structure

```
which-police-force/
├── mod_whichpoliceforce.xml    # Joomla manifest file
├── mod_whichpoliceforce.php    # Module entry point
├── mod_whichpoliceforce_ajax.php # AJAX handler
├── helper.php                   # Business logic
├── tmpl/
│   └── default.php             # Frontend template
├── assets/
│   ├── css/
│   │   └── style.css          # Module styles
│   └── js/
│       └── script.js          # Frontend JavaScript
├── language/
│   └── en-GB/                  # Language files
│       ├── mod_whichpoliceforce.ini
│       └── mod_whichpoliceforce.sys.ini
├── scripts/                    # Development scripts
│   ├── setup-dev.sh
│   ├── build.sh
│   └── test-install.sh
└── docs/
    └── VSCODE-SETUP.md        # VS Code setup guide
```

## Author

Philip Walton (phil@softforge.co.uk)

---
Copyright (C) 2025 SoftForge. All rights reserved.

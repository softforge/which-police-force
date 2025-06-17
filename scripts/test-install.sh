#!/bin/bash
# Test clean installation of Which Police Force module
# This script builds the module and installs it fresh in the test site

set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

echo "Testing clean installation of Which Police Force module..."

# First, build the package
echo "Building module package..."
"$SCRIPT_DIR/build.sh"

# Get the package name
VERSION=$(grep -oP '(?<=<version>)[^<]+' "$PROJECT_DIR/mod_whichpoliceforce.xml" || echo "1.0.0")
PACKAGE="$PROJECT_DIR/dist/mod_whichpoliceforce_v${VERSION}.zip"

if [ ! -f "$PACKAGE" ]; then
    echo "Error: Package not found at $PACKAGE"
    exit 1
fi

# Check if test site exists
if [ ! -d "/home/phil/sites/whichpolice-test" ]; then
    echo "Error: Test site not found at /home/phil/sites/whichpolice-test"
    exit 1
fi

# Remove symlink if exists
if [ -L "/home/phil/sites/whichpolice-test/modules/mod_whichpoliceforce" ]; then
    echo "Removing development symlink..."
    sudo rm /home/phil/sites/whichpolice-test/modules/mod_whichpoliceforce
fi

# Install via Joomla CLI
echo "Installing module via Joomla CLI..."
cd /home/phil/sites/whichpolice-test
sudo -u www-data php cli/joomla.php extension:install --path="$PACKAGE"

echo ""
echo "✓ Clean installation complete!"
echo "✓ Module installed from package"
echo ""
echo "Next steps:"
echo "1. Log into Joomla admin"
echo "2. Go to Content → Site Modules"
echo "3. Find 'Which Police Force?' module"
echo "4. Configure and assign to a module position"
echo ""
echo "To return to development mode, run: $SCRIPT_DIR/setup-dev.sh"
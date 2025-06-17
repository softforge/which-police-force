#!/bin/bash
# Setup development environment for Which Police Force module
# Re-establishes symlink if needed

echo "Setting up Which Police Force development environment..."

# Check if Joomla test site exists
if [ ! -d "/home/phil/sites/whichpolice-test" ]; then
    echo "Error: Test site not found at /home/phil/sites/whichpolice-test"
    echo "Please run the full setup first."
    exit 1
fi

# Create/update symlink
echo "Creating development symlink..."
sudo ln -sfn /home/phil/which-police-force /home/phil/sites/whichpolice-test/modules/mod_whichpoliceforce

# Check if successful
if [ -L "/home/phil/sites/whichpolice-test/modules/mod_whichpoliceforce" ]; then
    echo "✓ Symlink created successfully"
    echo "✓ Development environment ready!"
    echo ""
    echo "Access your test site at:"
    echo "http://forge.armadillo-firefighter.ts.net/whichpolice-test/"
    echo ""
    echo "Any changes to files in /home/phil/which-police-force will appear immediately in Joomla."
else
    echo "✗ Failed to create symlink"
    exit 1
fi
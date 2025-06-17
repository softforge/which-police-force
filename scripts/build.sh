#!/bin/bash
# Build installation package for Which Police Force module
# Creates a distributable ZIP file

set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
BUILD_DIR="$PROJECT_DIR/build"
DIST_DIR="$PROJECT_DIR/dist"

# Get version from XML
VERSION=$(grep -oP '(?<=<version>)[^<]+' "$PROJECT_DIR/mod_whichpoliceforce.xml" || echo "1.0.0")

echo "Building Which Police Force module v$VERSION..."

# Clean previous builds
echo "Cleaning previous builds..."
rm -rf "$BUILD_DIR" "$DIST_DIR"
mkdir -p "$BUILD_DIR" "$DIST_DIR"

# Copy files for distribution
echo "Copying files..."
cp "$PROJECT_DIR/mod_whichpoliceforce.xml" "$BUILD_DIR/"
cp "$PROJECT_DIR/mod_whichpoliceforce.php" "$BUILD_DIR/"
cp "$PROJECT_DIR/helper.php" "$BUILD_DIR/"

# Copy directories
cp -r "$PROJECT_DIR/tmpl" "$BUILD_DIR/"
cp -r "$PROJECT_DIR/language" "$BUILD_DIR/"
cp -r "$PROJECT_DIR/assets" "$BUILD_DIR/"

# Copy AJAX handler if exists
if [ -f "$PROJECT_DIR/mod_whichpoliceforce_ajax.php" ]; then
    cp "$PROJECT_DIR/mod_whichpoliceforce_ajax.php" "$BUILD_DIR/"
fi

# Create ZIP package
PACKAGE_NAME="mod_whichpoliceforce_v${VERSION}.zip"
echo "Creating package: $PACKAGE_NAME"
cd "$BUILD_DIR"
zip -r "$DIST_DIR/$PACKAGE_NAME" .
cd - > /dev/null

# Clean build directory
rm -rf "$BUILD_DIR"

echo ""
echo "✓ Build complete!"
echo "✓ Package created: $DIST_DIR/$PACKAGE_NAME"
echo ""
echo "To install in Joomla:"
echo "1. Go to System → Install → Extensions"
echo "2. Upload the package file"
echo "3. Configure and publish the module"
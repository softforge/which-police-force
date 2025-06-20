# VS Code Remote Development Setup

This guide will help you set up VS Code on your laptop to edit files on the forge server remotely.

## Prerequisites

- VS Code installed on your laptop
- SSH access to forge server
- Your SSH key configured for forge access

## Setup Steps

### 1. Install VS Code

Download and install VS Code from: https://code.visualstudio.com/

### 2. Install Remote-SSH Extension

1. Open VS Code
2. Click the Extensions icon (or press `Ctrl+Shift+X`)
3. Search for "Remote - SSH"
4. Install the official Microsoft extension

### 3. Configure SSH Connection

1. Press `F1` or `Ctrl+Shift+P` to open command palette
2. Type "Remote-SSH: Add New SSH Host"
3. Enter: `ssh phil@forge.armadillo-firefighter.ts.net`
4. Select the SSH config file to save to (usually `~/.ssh/config`)

### 4. Connect to Forge

1. Press `F1` and type "Remote-SSH: Connect to Host"
2. Select `forge.armadillo-firefighter.ts.net`
3. Wait for connection to establish
4. If prompted, select "Linux" as the platform

### 5. Open Project Folder

1. Once connected, click "Open Folder"
2. Navigate to: `/home/phil/repos/extensions/modules/which-police-force`
3. Click "OK"
4. Trust the authors if prompted

## Recommended Extensions

After connecting, install these extensions on the remote server:

- **PHP Intelephense** - PHP code intelligence
- **Joomla Snippets** - Joomla code snippets
- **GitLens** - Enhanced Git capabilities
- **Prettier** - Code formatter
- **Todo Tree** - TODO comment highlighter

## Working with the Module

### File Structure
```
which-police-force/
├── mod_whichpoliceforce.php    # Module entry point
├── helper.php                   # Business logic
├── tmpl/
│   └── default.php             # Frontend template
├── assets/
│   ├── css/
│   │   └── style.css          # Module styles
│   └── js/
│       └── script.js          # Frontend JavaScript
└── language/
    └── en-GB/                  # Language files
```

### Development Workflow

1. **Edit files** - Changes are automatically saved to forge
2. **Test changes** - Refresh browser at:
   http://forge.armadillo-firefighter.ts.net/whichpolice-test/
3. **Git operations** - Use VS Code's built-in Git support
4. **Terminal access** - Use VS Code's integrated terminal for commands

### Useful VS Code Shortcuts

- `Ctrl+S` - Save file
- `Ctrl+P` - Quick file open
- `Ctrl+Shift+F` - Search across files
- `Ctrl+` ` - Open integrated terminal
- `Ctrl+Shift+G` - Open Git panel

## Troubleshooting

### Connection Issues
- Ensure you can SSH to forge from terminal first
- Check your SSH key is added to ssh-agent
- Try: `ssh-add ~/.ssh/your_key`

### Permission Issues
- Some operations may need sudo
- Use integrated terminal for such commands

### Performance
- If slow, check your internet connection
- Consider disabling unnecessary extensions

## Alternative: Direct SSH Editing

If you prefer command-line editing:
```bash
ssh phil@forge.armadillo-firefighter.ts.net
cd ~/repos/extensions/modules/which-police-force
vim mod_whichpoliceforce.php
```

But VS Code Remote provides a much richer development experience!
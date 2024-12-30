#!/bin/bash

# Load setup scripts
source "$(dirname "$0")/common.sh"
source "$(dirname "$0")/connections.sh"
source "$(dirname "$0")/extensions.sh"
source "$(dirname "$0")/pages.sh"
source "$(dirname "$0")/plugins-extended.sh"
source "$(dirname "$0")/plugins-mu.sh"
source "$(dirname "$0")/plugins.sh"
source "$(dirname "$0")/themes.sh"

### Pages ###

# About Page Setup
function setup_page_about() {
    echo "Setting up About Page..."

    # Define additional files specific to the About Page
    local ADDITIONAL_FILES=(
        "$PAGES_DIR/about/block.json"
        "$PAGES_DIR/about/init.php"
    )

    # Call generic page setup for "about"
    setup_page "about"

    # Create additional About-specific files
    create_files "${ADDITIONAL_FILES[@]}"

    # Write content to About-specific files
    write_to_file "$PAGES_DIR/about/index.php" "<?php\n// About Page Content"
    write_to_file "$PAGES_DIR/about/README.md" "# About Page\n\nThis is the About Page."
    write_to_file "$PAGES_DIR/about/block.json" "{\n  \"name\": \"about\",\n  \"description\": \"Block settings for the About Page.\"\n}"
    write_to_file "$PAGES_DIR/about/init.php" "<?php\n// Initialization logic for the About Page"

    echo "About Page setup complete."
}

### Connections ###

# Example Connection Setup
function setup_connection_example() {
    echo "Setting up Example Connection..."

    # Define additional files for the Example Connection
    local ADDITIONAL_FILES=(
        "$CONNECTIONS_DIR/example-connection/tests/example-test.php"
    )

    # Create directories and files
    create_directories "$CONNECTIONS_DIR/example-connection/src" "$CONNECTIONS_DIR/example-connection/tests"
    create_files "$CONNECTIONS_DIR/example-connection/README.md" "$CONNECTIONS_DIR/example-connection/src/init.php" "${ADDITIONAL_FILES[@]}"

    # Write content to Example Connection files
    write_to_file "$CONNECTIONS_DIR/example-connection/README.md" "# Example Connection\n\nDetails about the Example Connection."
    write_to_file "$CONNECTIONS_DIR/example-connection/src/init.php" "<?php\n// Initialize the Example Connection"
    write_to_file "$CONNECTIONS_DIR/example-connection/tests/example-test.php" "<?php\n// Test cases for Example Connection"

    echo "Example Connection setup complete."
}

### Extensions ###

# Coda Extension Setup
function setup_extension_coda() {
    echo "Setting up Coda Extension..."

    # Create directories and files
    create_directories "$EXTENSIONS_DIR/coda"
    create_files "$EXTENSIONS_DIR/coda/init.php"

    # Write content to Coda Extension file
    write_to_file "$EXTENSIONS_DIR/coda/init.php" "<?php\n// Initialize Coda Extension"

    echo "Coda Extension setup complete."
}

# Expo Extension Setup
function setup_extension_expo() {
    echo "Setting up Expo Extension..."

    # Create directories and files
    create_directories "$EXTENSIONS_DIR/expo"
    create_files "$EXTENSIONS_DIR/expo/init.php"

    # Write content to Expo Extension file
    write_to_file "$EXTENSIONS_DIR/expo/init.php" "<?php\n// Initialize Expo Extension"

    echo "Expo Extension setup complete."
}

# Plasmo Extension Setup
function setup_extension_plasmo() {
    echo "Setting up Plasmo Extension..."

    # Create directories and files
    create_directories "$EXTENSIONS_DIR/plasmo"
    create_files "$EXTENSIONS_DIR/plasmo/init.php"

    # Write content to Plasmo Extension file
    write_to_file "$EXTENSIONS_DIR/plasmo/init.php" "<?php\n// Initialize Plasmo Extension"

    echo "Plasmo Extension setup complete."
}

# PWA Extension Setup
function setup_extension_pwa() {
    echo "Setting up PWA Extension..."

    # Create directories and files
    create_directories "$EXTENSIONS_DIR/pwa"
    create_files "$EXTENSIONS_DIR/pwa/init.php"

    # Write content to PWA Extension file
    write_to_file "$EXTENSIONS_DIR/pwa/init.php" "<?php\n// Initialize PWA Extension"

    echo "PWA Extension setup complete."
}

### Themes ###

# ExamplePress Theme Setup
function setup_theme_examplepress() {
    echo "Setting up ExamplePress Theme..."

    # Directories
    local THEME_ASSETS_DIR="$THEMES_DIR/examplepress/assets"
    local THEME_PARTS_DIR="$THEMES_DIR/examplepress/parts"
    local THEME_TEMPLATES_DIR="$THEMES_DIR/examplepress/templates"
    local THEME_FILES=(
        "$THEMES_DIR/examplepress/functions.php"
        "$THEMES_DIR/examplepress/style.css"
        "$THEMES_DIR/examplepress/README.md"
    )

    # Parts and Template files
    local PART_FILES=(
        "article-part-page.html"
        "header-part-404.html"
        "header-part-front-page.html"
    )
    local TEMPLATE_FILES=(
        "404.html"
        "archive.html"
        "index.html"
    )

    # Create Directories
    create_directories "$THEMES_DIR/examplepress" "$THEME_ASSETS_DIR" "$THEME_PARTS_DIR" "$THEME_TEMPLATES_DIR"

    # Create Core Files
    create_files "${THEME_FILES[@]}"
    write_to_file "$THEMES_DIR/examplepress/functions.php" "<?php\n// ExamplePress Theme Functions\n\n// Enqueue scripts and styles\nadd_action('wp_enqueue_scripts', function() {\n    wp_enqueue_style('examplepress-style', get_stylesheet_uri());\n});"
    write_to_file "$THEMES_DIR/examplepress/style.css" "/*\nTheme Name: ExamplePress\nTheme URI: https://example.com\nAuthor: Example Author\nDescription: ExamplePress WordPress Theme\nVersion: 1.0\n*/"
    write_to_file "$THEMES_DIR/examplepress/README.md" "# ExamplePress Theme\n\nThis is the ExamplePress theme."

    # Create Part and Template Files
    for PART in "${PART_FILES[@]}"; do
        write_to_file "$THEME_PARTS_DIR/$PART" "<!-- ExamplePress: $PART -->"
    done
    for TEMPLATE in "${TEMPLATE_FILES[@]}"; do
        write_to_file "$THEME_TEMPLATES_DIR/$TEMPLATE" "<!-- ExamplePress Template: $TEMPLATE -->"
    done

    # Populate Assets
    echo "Populating assets..."
    create_files "$THEME_ASSETS_DIR/icons/example-icon.svg" "$THEME_ASSETS_DIR/scripts/example-script.js"
    write_to_file "$THEME_ASSETS_DIR/icons/example-icon.svg" "<svg></svg>"
    write_to_file "$THEME_ASSETS_DIR/scripts/example-script.js" "// Example JavaScript file for ExamplePress Theme"

    echo "ExamplePress Theme setup complete."
}

### Must-Use Plugins ###

# ExamplePress Core Plugin Setup
function setup_mu_plugin_examplepress_core() {
    echo "Setting up ExamplePress Core Plugin..."

    # Use setup_must_use_plugin to streamline directory and file creation
    setup_must_use_plugin "examplepress" "" ""

    # Write content to ExamplePress Core Plugin files
    write_to_file "$MU_PLUGINS_DIR/examplepress.php" "<?php\n// Core ExamplePress Functions\n\n// Add your core logic here."
    write_to_file "$MU_PLUGINS_DIR/examplepress/README.md" "# ExamplePress Core\n\nThis directory contains the core functions for ExamplePress."

    echo "ExamplePress Core Plugin setup complete."
}

# Authentication Plugin Setup
function setup_mu_plugin_examplepress_auth() {
    echo "Setting up ExamplePress Authentication Plugin..."

    # Additional directories and files for authentication logic
    local ADDITIONAL_DIRS=("$MU_PLUGINS_DIR/examplepress-auth/tests")
    local ADDITIONAL_FILES=("$MU_PLUGINS_DIR/examplepress-auth/tests/auth-test.php")

    # Use setup_must_use_plugin
    setup_must_use_plugin "examplepress-auth" ADDITIONAL_DIRS[@] ADDITIONAL_FILES[@]

    # Write content to Authentication Plugin files
    write_to_file "$MU_PLUGINS_DIR/examplepress-auth.php" "<?php\n// ExamplePress Auth Entry Point\n\n// Add your authentication logic here."
    write_to_file "$MU_PLUGINS_DIR/examplepress-auth/auth-main.php" "<?php\n// ExamplePress Auth Plugin Main Logic\n\n// Authentication plugin-specific logic."
    write_to_file "${ADDITIONAL_FILES[0]}" "<?php\n// Test cases for ExamplePress Auth"

    echo "ExamplePress Authentication Plugin setup complete."
}

# Brand Plugin Setup
function setup_mu_plugin_examplepress_brand() {
    echo "Setting up ExamplePress Brand Plugin..."

    # Additional files for branding configuration
    local ADDITIONAL_FILES=("$MU_PLUGINS_DIR/examplepress-brand/branding-config.php")

    # Use setup_must_use_plugin
    setup_must_use_plugin "examplepress-brand" "" ADDITIONAL_FILES[@]

    # Write content to branding-specific files
    write_to_file "$MU_PLUGINS_DIR/examplepress-brand.php" "<?php\n// ExamplePress Brand Entry Point\n\n// Branding-specific entry logic."
    write_to_file "$MU_PLUGINS_DIR/examplepress-brand/branding-config.php" "<?php\n// Example branding configuration logic."

    echo "ExamplePress Brand Plugin setup complete."
}

# Theme Plugin Setup
function setup_mu_plugin_examplepress_theme() {
    echo "Setting up ExamplePress Theme Plugin..."

    # Additional directories for theme assets
    local ADDITIONAL_DIRS=("$MU_PLUGINS_DIR/examplepress-theme/assets")

    # Use setup_must_use_plugin
    setup_must_use_plugin "examplepress-theme" ADDITIONAL_DIRS[@] ""

    # Write content to theme-specific files
    write_to_file "$MU_PLUGINS_DIR/examplepress-theme/examplepress-theme.php" "<?php\n// ExamplePress Theme Plugin Main File\n\n// Theme-related logic here."

    echo "ExamplePress Theme Plugin setup complete."
}

# Template Plugin Setup
function setup_mu_plugin_examplepress_template() {
    echo "Setting up ExamplePress Template Plugin..."

    # Placeholder for additional files
    local ADDITIONAL_FILES=("$MU_PLUGINS_DIR/examplepress-template/template-config.php")

    # Use setup_must_use_plugin
    setup_must_use_plugin "examplepress-template" "" ADDITIONAL_FILES[@]

    # Write content to template-specific files
    write_to_file "$MU_PLUGINS_DIR/examplepress-template.php" "<?php\n// ExamplePress Template Plugin Entry Point\n\n// Template plugin logic."
    write_to_file "${ADDITIONAL_FILES[0]}" "<?php\n// Example template configuration logic."

    echo "ExamplePress Template Plugin setup complete."
}

### Default Plugins ###

# ExamplePress Template Plugin Setup
function setup_plugin_examplepress_template() {
    echo "Setting up examplepress-template plugin..."

    # Additional files specific to the ExamplePress Template Plugin
    local ADDITIONAL_FILES=(
        "src/activate.php"
        "src/deactivate.php"
        "src/uninstall.php"
    )

    # Use the generic plugin setup
    setup_plugin "examplepress-template" "" ADDITIONAL_FILES[@]

    # Write specific content for ExamplePress Template Plugin
    write_to_file "$PLUGINS_DIR/examplepress-template/examplepress-template.php" "<?php\n// ExamplePress Template Plugin Entry Point"
    write_to_file "$PLUGINS_DIR/examplepress-template/src/activate.php" "<?php\n// Activation Logic for ExamplePress Template"
    write_to_file "$PLUGINS_DIR/examplepress-template/src/deactivate.php" "<?php\n// Deactivation Logic for ExamplePress Template"
    write_to_file "$PLUGINS_DIR/examplepress-template/src/uninstall.php" "<?php\n// Uninstallation Logic for ExamplePress Template"

    echo "ExamplePress Template Plugin setup complete."
}

### Extended Plugins ###

# ExamplePress Extended Plugin
function setup_extended_plugin_examplepress() {
    echo "Setting up extend-plugin-examplepress..."

    # Define any additional directories or files specific to this plugin
    local ADDITIONAL_DIRS=("$PLUGINS_DIR/extend-plugin-examplepress/tests")
    local ADDITIONAL_FILES=(
        "$PLUGINS_DIR/extend-plugin-examplepress/tests/plugin-test.php"
    )

    # Use the generic setup function
    setup_extended_plugin "extend-plugin-examplepress" ADDITIONAL_DIRS[@] ADDITIONAL_FILES[@]

    # Write example content for this specific plugin
    write_to_file "$PLUGINS_DIR/extend-plugin-examplepress/extend-plugin-examplepress.php" "<?php\n// Extend Plugin ExamplePress Entry Point"
    write_to_file "$PLUGINS_DIR/extend-plugin-examplepress/README.md" "# Extend Plugin ExamplePress\n\nDetails about the Extend Plugin ExamplePress."
    write_to_file "$PLUGINS_DIR/extend-plugin-examplepress/tests/plugin-test.php" "<?php\n// Test cases for Extend Plugin ExamplePress"

    echo "Extend Plugin ExamplePress setup complete."
}

### Main Setup Function ###

# Main setup function
function setup_examplepress() {
    echo "Starting ExamplePress setup..."

    # Step 1: Create base directories
    echo "Creating base directories..."
    create_directories "$BASE_DIR" "$MU_PLUGINS_DIR" "$PLUGINS_DIR" "$THEMES_DIR" "$CONNECTIONS_DIR" "$EXTENSIONS_DIR" "$PAGES_DIR"

    # Step 2: Set up individual components

    # Pages
    echo "Setting up pages..."
    setup_page_about

    # Connections
    echo "Setting up connections..."
    setup_connection_example

    # Extensions
    echo "Setting up extensions..."
    setup_extension_coda
    setup_extension_expo
    setup_extension_plasmo
    setup_extension_pwa

    # Themes
    echo "Setting up themes..."
    setup_theme_examplepress

    # Must-Use Plugins (MU Plugins)
    echo "Setting up MU Plugins..."
    setup_mu_plugin_examplepress_core
    setup_mu_plugin_examplepress_auth
    setup_mu_plugin_examplepress_brand
    setup_mu_plugin_examplepress_theme
    setup_mu_plugin_examplepress_template

    # Standard Plugins
    echo "Setting up standard plugins..."
    setup_plugin_examplepress_template

    # Extended Plugins
    echo "Setting up extended plugins..."
    setup_extended_plugin_examplepress

    echo "ExamplePress setup complete."
}

# Execute the main setup function
setup_examplepress
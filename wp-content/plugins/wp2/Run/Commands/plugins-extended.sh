#!/bin/bash

# Load core utilities
source "$(dirname "$0")/common.sh"

# Generic Extended Plugin Setup
function setup_extended_plugin() {
    local PLUGIN_NAME="$1"
    local ADDITIONAL_DIRS=("${!2}")  # Optional additional directories
    local ADDITIONAL_FILES=("${!3}") # Optional additional files

    echo "Setting up extended plugin: $PLUGIN_NAME..."

    # Default directories and files for the plugin
    local DIRECTORIES=(
        "$PLUGINS_DIR/$PLUGIN_NAME"
        "$PLUGINS_DIR/$PLUGIN_NAME/src"
        "${ADDITIONAL_DIRS[@]}" # Add any additional directories
    )
    local FILES=(
        "$PLUGINS_DIR/$PLUGIN_NAME/$PLUGIN_NAME.php"
        "$PLUGINS_DIR/$PLUGIN_NAME/README.md"
        "${ADDITIONAL_FILES[@]}" # Add any additional files
    )

    # Create directories and files
    create_directories "${DIRECTORIES[@]}"
    create_files "${FILES[@]}"

    # Write default content
    write_to_file "$PLUGINS_DIR/$PLUGIN_NAME/$PLUGIN_NAME.php" "<?php\n// Main entry point for the $PLUGIN_NAME plugin."
    write_to_file "$PLUGINS_DIR/$PLUGIN_NAME/README.md" "# $PLUGIN_NAME Plugin\n\nDetails about the $PLUGIN_NAME plugin."

    echo "Extended Plugin $PLUGIN_NAME setup complete."
}
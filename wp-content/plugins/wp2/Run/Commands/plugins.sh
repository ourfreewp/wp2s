#!/bin/bash

# Load core utilities
source "$(dirname "$0")/common.sh"

# Generic Plugin Setup
function setup_plugin() {
    local PLUGIN_NAME="$1"
    local ADDITIONAL_DIRS=("${!2}") # Optional: Additional directories as an array
    local ADDITIONAL_FILES=("${!3}") # Optional: Additional files as an array

    echo "Setting up Plugin: $PLUGIN_NAME..."

    # Core directories and files
    local CORE_DIRS=(
        "$PLUGINS_DIR/$PLUGIN_NAME"
        "$PLUGINS_DIR/$PLUGIN_NAME/src"
    )
    local CORE_FILES=(
        "$PLUGINS_DIR/$PLUGIN_NAME/$PLUGIN_NAME.php"
        "$PLUGINS_DIR/$PLUGIN_NAME/uninstall.php"
        "$PLUGINS_DIR/$PLUGIN_NAME/README.md"
    )

    # Combine core and additional directories
    local ALL_DIRS=("${CORE_DIRS[@]}" "${ADDITIONAL_DIRS[@]}")
    local ALL_FILES=("${CORE_FILES[@]}" "${ADDITIONAL_FILES[@]}")

    # Create directories
    create_directories "${ALL_DIRS[@]}"

    # Create files
    create_files "${ALL_FILES[@]}"

    # Write default content for core files
    write_to_file "$PLUGINS_DIR/$PLUGIN_NAME/$PLUGIN_NAME.php" "<?php\n// Entry point for $PLUGIN_NAME Plugin."
    write_to_file "$PLUGINS_DIR/$PLUGIN_NAME/uninstall.php" "<?php\n// Uninstall logic for $PLUGIN_NAME Plugin."
    write_to_file "$PLUGINS_DIR/$PLUGIN_NAME/README.md" "# $PLUGIN_NAME Plugin\n\nDetails about the $PLUGIN_NAME plugin."

    echo "Plugin $PLUGIN_NAME setup complete."
}
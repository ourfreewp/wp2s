#!/bin/bash

# Load core utilities
source "$(dirname "$0")/common.sh"

# Generic Must-Use Plugin Setup
function setup_mu_plugin() {
    local PLUGIN_NAME="$1"
    local ADDITIONAL_DIRS=("${!2}")  # Optional additional directories
    local ADDITIONAL_FILES=("${!3}") # Optional additional files

    echo "Setting up Must-Use Plugin: $PLUGIN_NAME..."

    # Default directories and files for the plugin
    local DIRECTORIES=(
        "$MU_PLUGINS_DIR/$PLUGIN_NAME"
        "$MU_PLUGINS_DIR/$PLUGIN_NAME/src"
        "${ADDITIONAL_DIRS[@]}" # Add any additional directories
    )
    local FILES=(
        "$MU_PLUGINS_DIR/$PLUGIN_NAME/$PLUGIN_NAME.php"
        "${ADDITIONAL_FILES[@]}" # Add any additional files
    )

    # Create directories and files
    create_directories "${DIRECTORIES[@]}"
    create_files "${FILES[@]}"

    # Write default content
    write_to_file "$MU_PLUGINS_DIR/$PLUGIN_NAME/$PLUGIN_NAME.php" "<?php\n// Main entry point for the $PLUGIN_NAME Must-Use Plugin."
    echo "Must-Use Plugin $PLUGIN_NAME setup complete."
}
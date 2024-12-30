#!/bin/bash

# Load core utilities
source "$(dirname "$0")/common.sh"

# Generic extension setup
function setup_extension() {
    local EXTENSION_NAME="$1"
    local ADDITIONAL_DIRS=("${!2}")  # Optional additional directories
    local ADDITIONAL_FILES=("${!3}") # Optional additional files

    echo "Setting up extension: $EXTENSION_NAME..."

    # Default directories and files for the extension
    local DIRECTORIES=(
        "$EXTENSIONS_DIR/$EXTENSION_NAME"
        "${ADDITIONAL_DIRS[@]}" # Add any additional directories
    )
    local FILES=(
        "$EXTENSIONS_DIR/$EXTENSION_NAME/init.php"
        "${ADDITIONAL_FILES[@]}" # Add any additional files
    )

    # Create directories and files
    create_directories "${DIRECTORIES[@]}"
    create_files "${FILES[@]}"

    # Write default content
    write_to_file "$EXTENSIONS_DIR/$EXTENSION_NAME/init.php" "<?php\n// Initialize $EXTENSION_NAME extension logic."

    echo "Extension $EXTENSION_NAME setup complete."
}
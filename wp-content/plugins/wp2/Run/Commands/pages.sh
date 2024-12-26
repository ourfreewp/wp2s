#!/bin/bash

# Load core utilities
source "$(dirname "$0")/common.sh"

# Generic page setup
function setup_page() {
    local PAGE_NAME="$1"
    local ADDITIONAL_DIRS=("${!2}")  # Optional additional directories
    local ADDITIONAL_FILES=("${!3}") # Optional additional files

    echo "Setting up page: $PAGE_NAME..."

    # Default directories and files for the page
    local DIRECTORIES=(
        "$PAGES_DIR/$PAGE_NAME"
        "${ADDITIONAL_DIRS[@]}" # Add any additional directories
    )
    local FILES=(
        "$PAGES_DIR/$PAGE_NAME/index.php"
        "$PAGES_DIR/$PAGE_NAME/README.md"
        "${ADDITIONAL_FILES[@]}" # Add any additional files
    )

    # Create directories and files
    create_directories "${DIRECTORIES[@]}"
    create_files "${FILES[@]}"

    # Write default content
    write_to_file "$PAGES_DIR/$PAGE_NAME/index.php" "<?php\n// Page: $PAGE_NAME content logic."
    write_to_file "$PAGES_DIR/$PAGE_NAME/README.md" "# $PAGE_NAME Page\n\nDetails about the $PAGE_NAME page."

    echo "Page $PAGE_NAME setup complete."
}
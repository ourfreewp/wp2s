#!/bin/bash

# Load core utilities
source "$(dirname "$0")/common.sh"

# Generic connection setup
function setup_connection() {
    local CONNECTION_NAME="$1"
    local ADDITIONAL_DIRS=("${!2}")  # Optional additional directories
    local ADDITIONAL_FILES=("${!3}") # Optional additional files

    echo "Setting up connection: $CONNECTION_NAME..."

    # Default directories and files for the connection
    local DIRECTORIES=(
        "$CONNECTIONS_DIR/$CONNECTION_NAME"
        "$CONNECTIONS_DIR/$CONNECTION_NAME/src"
        "$CONNECTIONS_DIR/$CONNECTION_NAME/tests"
        "${ADDITIONAL_DIRS[@]}" # Add any additional directories
    )
    local FILES=(
        "$CONNECTIONS_DIR/$CONNECTION_NAME/README.md"
        "$CONNECTIONS_DIR/$CONNECTION_NAME/src/init.php"
        "${ADDITIONAL_FILES[@]}" # Add any additional files
    )

    # Create directories and files
    create_directories "${DIRECTORIES[@]}"
    create_files "${FILES[@]}"

    # Write default content
    write_to_file "$CONNECTIONS_DIR/$CONNECTION_NAME/README.md" "# $CONNECTION_NAME Connection\n\nDetails about the $CONNECTION_NAME connection."
    write_to_file "$CONNECTIONS_DIR/$CONNECTION_NAME/src/init.php" "<?php\n// Initialize $CONNECTION_NAME connection logic."

    echo "Connection $CONNECTION_NAME setup complete."
}
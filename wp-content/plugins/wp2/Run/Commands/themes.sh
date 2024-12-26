#!/bin/bash

# Load core utilities
source "$(dirname "$0")/common.sh"

# Generic Theme Setup
function setup_theme() {
    local THEME_NAME="$1"
    local DIRECTORIES=(
        "$THEMES_DIR/$THEME_NAME"
        "$THEMES_DIR/$THEME_NAME/assets"
        "$THEMES_DIR/$THEME_NAME/assets/fonts"
        "$THEMES_DIR/$THEME_NAME/assets/icons"
        "$THEMES_DIR/$THEME_NAME/assets/scripts"
        "$THEMES_DIR/$THEME_NAME/assets/styles"
        "$THEMES_DIR/$THEME_NAME/parts"
        "$THEMES_DIR/$THEME_NAME/templates"
    )
    create_directories "${DIRECTORIES[@]}"
    echo "Theme $THEME_NAME setup complete."
}
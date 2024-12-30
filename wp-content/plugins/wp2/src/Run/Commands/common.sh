#!/bin/bash

# Core utility functions

# Function to create directories with error handling
function create_directories() {
    if [[ $# -eq 0 ]]; then
        echo "Error: No directories specified for creation." >&2
        exit 1
    fi
    for DIR in "$@"; do
        mkdir -p "$DIR"
        if [[ $? -ne 0 ]]; then
            echo "Error: Failed to create directory $DIR" >&2
            exit 1
        fi
        echo "Created directory: $DIR"
    done
}

# Function to create files with error handling
function create_files() {
    if [[ $# -eq 0 ]]; then
        echo "Error: No files specified for creation." >&2
        exit 1
    fi
    for FILE in "$@"; do
        touch "$FILE"
        if [[ $? -ne 0 ]]; then
            echo "Error: Failed to create file $FILE" >&2
            exit 1
        fi
        echo "Created file: $FILE"
    done
}

# Centralized file content definitions
declare -A FILE_CONTENTS=(
    ["about/index.php"]="<?php\n// About Page Content\n// Created on {{DATE}}"
    ["about/README.md"]="# About Page\n\nThis is the About Page."
    ["examplepress-template/examplepress-template.php"]="<?php\n// ExamplePress Template Plugin Entry Point"
    ["examplepress-template/src/activate.php"]="<?php\n// Activation Logic for ExamplePress Template"
)

# Write content using centralized definitions
function write_content() {
    local FILE="$1"
    local CONTENT="${FILE_CONTENTS[$FILE]}"
    if [[ -n "$CONTENT" ]]; then
        # Replace placeholders dynamically
        CONTENT="${CONTENT//{{DATE}}/$(date +%Y-%m-%d)}"
        echo "$CONTENT" > "$FILE"
        echo "Wrote content to $FILE"
    else
        echo "Error: No content defined for $FILE" >&2
        exit 1
    fi
}

# Optional logging
LOG_FILE="setup.log"

function log_message() {
    local MESSAGE="$1"
    echo "$(date +'%Y-%m-%d %H:%M:%S') - $MESSAGE" | tee -a "$LOG_FILE"
}
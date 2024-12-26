# Must Use Studios

**Must Use Studios** is a WordPress plugin suite designed to provide a robust framework for managing custom post types, taxonomies, and other essential functionalities within a WordPress environment. The plugin is intended to be used as a Must-Use (MU) plugin, ensuring it is always active and cannot be deactivated via the standard WordPress plugin interface.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [File Structure](#file-structure)
- [Configuration](#configuration)
- [Error Handling](#error-handling)
- [Development](#development)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Custom Post Types and Taxonomies:** Easily register and manage custom post types and taxonomies.
- **Configuration Management:** Centralized configuration settings for managing plugin behavior.
- **Error Handling:** Robust error handling and logging for better debugging and maintenance.
- **File and Directory Validation:** Automated validation of required files and directories.
- **Syncing:** Synchronization between the filesystem and WordPress database.
- **REST API Integration:** Custom REST API endpoints for extended functionality.

## Installation

### Manual Installation

1. **Download the Plugin:**
   - Download the plugin files from the repository or clone the repository.

2. **Upload the Plugin:**
   - Upload the `mustuse-studios` folder to the `mu-plugins` directory in your WordPress installation. If the `mu-plugins` directory does not exist, create it in the `wp-content` directory.

3. **Ensure Proper Naming:**
   - The loader file `_mustuse-studios.php` should be placed directly in the `mu-plugins` directory.

4. **Activate the Plugin:**
   - The plugin is automatically activated when placed in the `mu-plugins` directory, as it is a Must-Use plugin.

## Usage

### Configuration

- **Configuring Studios:**
  - The plugin allows you to configure custom post types (studios) and taxonomies using the `Configurator.php` and `Registrar.php` files.
  
- **Syncing Studios:**
  - The `SyncManager.php` file handles syncing between the filesystem and the WordPress database. This ensures that any changes made in the filesystem are reflected in the WordPress backend.

### Error Handling

- **Error Logging:**
  - The `ErrorHandler.php` file provides a centralized way to log and handle errors across the plugin. Errors are logged to the WordPress debug log by default.

## File Structure

```plaintext
mu-plugins/
│
├── _mustuse-studios.php            # Loader file for the Must Use Studios plugin suite
└── mustuse-studios/
    ├── Config.php                  # Configuration management
    ├── ErrorHandler.php            # Error handling and logging
    ├── Loader.php                  # Main plugin loader
    ├── MustUseStudios.php          # Main plugin class
    ├── Configurator.php            # Studio configuration class
    ├── Registrar.php               # Studio registration class
    ├── Validator.php               # File and directory validation class
    ├── SyncManager.php             # Synchronization management
    ├── TaxonomyManager.php         # Taxonomy registration and management
    ├── Helpers/
    │   ├── Utils.php               # Utility functions
    │   └── Logger.php              # Logger utility
    ├── REST/
    │   └── StudioController.php    # REST API controller for studios
    └── languages/
        └── mustuse-studios.pot     # Language translation template
```

## Configuration

- **Editing Configuration:**
  - Modify the `Config.php` file to add or change configuration settings. Use the `Config::set()` and `Config::get()` methods to manage settings programmatically.

- **Custom Post Types and Taxonomies:**
  - Add your custom post types in the `Registrar.php` file.
  - Register custom taxonomies in the `TaxonomyManager.php` file.

## Error Handling

- **Handling Errors:**
  - Use `ErrorHandler::handle()` to log exceptions or errors.
  - Errors are logged to the WordPress debug log by default, but you can customize this in the `ErrorHandler.php` file.

## Development

### Requirements

- **WordPress 5.0+**
- **PHP 7.0+**

### Code Standards

- Follow the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) for PHP, HTML, CSS, and JavaScript.

### Contributing

- **Reporting Issues:**
  - Use the issue tracker on GitHub to report bugs or suggest enhancements.
  
- **Pull Requests:**
  - Fork the repository, create a new branch, and submit a pull request with your changes.

## License
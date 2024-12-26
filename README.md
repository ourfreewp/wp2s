# WP2S

## Table of Contents

- [Structure](#structure)

## Structure

- **wp-config.php**  
  Core configuration settings, including database connections and authentication keys.

- **wp-content/**  
  Primary directory for user content, including plugins, themes, and uploads.

- **wp-content/debug.log**  
  Log file for debugging purposes, capturing errors and warnings when `WP_DEBUG` is enabled.

- **wp-content/plugins/**  
  Directory containing all WordPress plugins, which extend or modify site functionality.

- **wp-content/plugins/textdomain-***  
  Custom plugins prefixed with `textdomain-`, providing specific functionalities or modules for the site.

- **wp-content/plugins/extend-***  
  Plugins prefixed with `extend-textdomain`, designed to extend or enhance existing features provided by other plugins.

- **wp-content/themes/**  
  Directory containing all WordPress themes, which dictate the site’s appearance and layout.

- **wp-content/themes/textdomain**  
    Custom theme named `textdomain`, providing the site’s design and layout.

- **wp-content/mu-plugins/**  
    Directory for must-use plugins, which are always active and loaded before regular plugins.

- **wp-content/mu-plugins/examplepress**  
    Custom must-use plugin named `textdomain`, containing essential site functionalities and configurations.

- **wp-content/mu-plugins/textdomain.php**  
    Main file for the custom must-use plugin, initializing the plugin and its components.

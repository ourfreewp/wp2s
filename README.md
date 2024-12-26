# WP2S

## Table of Contents

- [Philosophy](#philosophy)
- [Structure](#structure)
- [Requirements](#requirements)
- [Modules](#modules)
- [Plugins](#plugins)
- [Blocks](#blocks)
- [Connections](#connections)
- [Experiences](#experiences)
- [Customization](#customization)

## Structure

- **wp-config.php**  
  Core configuration settings, including database connections and authentication keys.

- **wp-content/**  
  Primary directory for user content, including plugins, themes, and uploads.

- **wp-content/debug.log**  
  Log file for debugging purposes, capturing errors and warnings when `WP_DEBUG` is enabled.

- **wp-content/plugins/**  
  Directory containing all WordPress plugins, which extend or modify site functionality.

- **wp-content/plugins/examplepress-***  
  Custom plugins prefixed with `examplepress-`, providing specific functionalities or modules for the site.

- **wp-content/plugins/extend-***  
  Plugins prefixed with `extend-`, designed to extend or enhance existing features provided by other plugins.

- **wp-content/themes/**  
  Directory containing all WordPress themes, which dictate the site’s appearance and layout.

- **wp-content/themes/examplepress**  
    Custom theme named `examplepress`, providing the site’s design and layout.

- **wp-content/mu-plugins/**  
    Directory for must-use plugins, which are always active and loaded before regular plugins.

- **wp-content/mu-plugins/examplepress**  
    Custom must-use plugin named `examplepress`, containing essential site functionalities and configurations.

- **wp-content/mu-plugins/examplepress.php**  
    Main file for the custom must-use plugin, initializing the plugin and its components.

```plaintext
src/
|-- wp-config.php
|-- wp-content/
    |-- mu-plugins/
        |-- examplepress/
            |-- api/
                |-- GraphQL/
                    |-- 
                |-- REST/
                    |-- 
            |-- blocks
            |-- comments
            |-- envs
            |-- fields
            |-- models
            |-- pages
            |-- posts // includes posts and types
            |-- shortcodes
            |-- tables
            |-- terms // includes terms and taxnomies
            |-- uploads
            |-- users 
        |-- examplepress.php
    |-- plugins/
        |-- examplepress-core/
            |-- src/
            |-- examplepress-core.php # Plugin Init
        |-- examplepress-*/         # Modules
    |-- themes/
        |-- examplepress/           # Theme
            |-- assets/             # Assets
                |-- fonts/          # Fonts
                    |-- *.*         # Font File
            |-- parts/              # Parts
                |-- {area}-part-{template}.html
                |-- root-{area}.html
            |-- patterns/           # Patterns
            |-- templates/          # Templates
                |-- 404.html         # 404 Template
                |-- archive.html     # Archive Template
                |-- author.html      # Author Template
                |-- front-page.html  # Front Page Template
                |-- index.html       # Index Template
                |-- page.html        # Page Template
                |-- search.html      # Search Template
                |-- single.html      # Single Template
            |-- functions.php       # Functions // Inert
            |-- style.css           # Header (legacy) // Inert
            |-- screenshot.png      # Screenshot
            |-- readme.txt          # Readme (legacy)
            |-- README.md           # Readme (markdown)
            |-- theme.json          # Theme Config
            |-- studio.json         # Studio Config
```

## Requirements

To use ExamplePress, you should have the following plugins installed and activated:

- Meta Box: Provides tools to create custom fields and integrates seamlessly with blocks.
- Blockstudio: Offers a robust block-based editing framework for building and managing content blocks efficiently.

## Modules

## Plugins

## Blocks

### Inherit

Core blocks are the default blocks that come with WordPress (such as paragraphs, images, headings, lists, etc.).

- Wrapping Core Blocks: ExamplePress can provide custom wrappers around core blocks to add new functionalities or styling wrappers.
- Extending Core Blocks: Additional attributes or styling can be added to default core blocks to integrate them seamlessly with the site’s design and functionalities.

### Import

Imported blocks are third-party blocks integrated into ExamplePress to provide specialized functionalities or layouts.

- Ours: Blocks developed or customized by ExamplePress for specific functionalities needed by the site.

### Build

Custom blocks are user-defined blocks that extend the functionality of the block editor to include specialized content types or layouts.

- Yours: Blocks that you create to meet specific requirements of your site or project, fully integrated into ExamplePress’s architecture.

## Connections

Connections are integrations or APIs that ExamplePress uses to connect WordPress with external services and platforms.

## Experiences

ExamplePress is designed to handle distinct user experiences within different site areas, each managed by a combination of modules, blocks, and connections.

### CMS — Content Management System

Enhances the core WordPress CMS functionalities with modules, blocks, and plugins that allow advanced content management, structured editing, and cross-platform data integration.

### CRM — Customer Relationship Management

Integrates functionalities that allow businesses to manage customer interactions, leads, and data directly within WordPress, often leveraging tools like HubSpot or custom modules.

### NEWS — News & Media

Tailored functionalities for news and media sites, including article management, media galleries, and metadata enhancements for better indexing and search engine visibility.

## Customization

### Scripts

Scripts are organized and loaded using specific naming conventions and prefixes to determine their loading context (frontend, editor, admin) and method (inline or not).

The naming convention includes:

- global- prefix for scripts that should load everywhere.
- editor- or view- suffix to specify whether the script should run in the block editor or in the public view.
- **-inline*- suffix if the script should be inlined in the page instead of being loaded as a separate file.
- admin- prefix for scripts exclusive to the WordPress admin area.
- block-editor- prefix for scripts loaded specifically in the block editor.

#### Global Scripts

Global scripts are loaded on every page of the site (both frontend and backend) unless otherwise specified. They are placed in the global-scripts.js file (and corresponding variants for editor and view contexts).

- global-scripts.js: Contains scripts that should be loaded across the entire site.
- global-scripts-editor.js: Contains scripts that should be loaded within the block editor context.
- global-scripts-view.js: Contains scripts that should be loaded in the public view (frontend) context.

#### Block Editor Scripts

Block editor scripts are specifically loaded only in the block editing interface of WordPress. These scripts enhance or modify the behavior of block editing.

- block-editor-scripts.js: Contains scripts that should be loaded exclusively within the block editor to extend block functionalities or editor UI.

#### Admin Scripts

Admin scripts are loaded only in the WordPress administration interface, enhancing or altering the admin experience.

- admin-scripts.js: Contains scripts that modify or add functionality to the WordPress admin area.

### Styles

Styles are similarly organized using specific filenames and naming conventions. All SCSS (Sass) files are compiled into CSS and placed into a /_dist directory using tools such as Blockstudio. The naming convention is aligned with scripts to maintain consistency.

The naming convention includes:

- global- prefix for styles that should apply everywhere.
- editor- or view- suffix to specify context (editor or public view).
- **-inline*- suffix if the style should be inlined in the document’s head.
- **-scoped*- suffix if the style should be scoped to a specific block or component.
- admin- prefix for styles exclusive to the admin area.
- block-editor- prefix for styles loaded specifically in the block editor.

#### Global Styles

Global styles apply to every page of the site.

- global-styles.scss: SCSS file compiled for global styles across the site.
- global-styles-editor.scss: SCSS file compiled for global styles specifically in the block editor.
- global-styles-view.scss: SCSS file compiled for global styles specifically on the public-facing side of the site.

#### Block Editor Styles

Block editor styles modify the appearance of blocks within the WordPress editor to ensure the editing experience matches the public appearance of blocks as closely as possible.

- block-editor-styles.scss: SCSS file compiled into CSS to style elements and blocks within the editor.

#### Admin Styles

Admin styles apply only to the WordPress administration interface, ensuring a consistent and user-friendly admin experience.

- admin-styles.scss: SCSS file compiled into CSS for customizing the WordPress admin area UI.


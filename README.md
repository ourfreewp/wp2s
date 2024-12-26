# WP2S

## Table of Contents

- [Structure](#structure)

## Structure

### Themes (/wp-content/themes)
Directory containing all WordPress themes, which control the look and feel of the site.

### Plugins (/wp-content/plugins)
Directory containing all WordPress plugins, which extend or modify site functionality.
#### Extensions (/wp-content/plugins/extend-*)
The `extend-*` directory contains the functionality that extends the core functionality of existing plugins. 

### MUs (/wp-content/mu-plugins)
The `mu-plugins` directory contains functionality that is always enabled and cannot be disabled from the WordPress admin area.

### WP2s (/wp-content/plugins/wp2-*)
The `wp2-*` directory contains the functionality assigend to each zone in the WP2S Network. Each WP2 is a zone, and each zone has a unique `textdomain` directory.

## Components

### Theme (/themes/wp2s)
The `wp2s` directory contains the primary theme for the installation.

### Plugin (/plugins/wp2s)
The `wp2s` directory contains the core files for the installation.

### WP2 Core (/plugins/wp2)
The `wp2` directory contains the WP2 modules that are not required to be in dedicated plugins.

### WP2 Express (/plugins/wp2-express)
The `wp2-express` directory contains the key functionality for creating new, fully configured installations.

## Zones
A zone is a collection of resources that share a common purpose or theme. Each zone has a unique directory that cooresponds to top-level domain that is generally available for registration.

- [Bio](#bio)
- [Blog](#blog)
- [Community](#community)
- [Contact](#contact)
- [Dev](#dev)
- [Express](#express)
- [Health](#health)
- [Legal](#legal)
- [Link](#link)
- [Marketing](#marketing)
- [Media](#media)
- [One](#one)
- [Pub](#pub)
- [REST](#rest)
- [Run](#run)
- [Sh](#sh)
- [Shop](#shop)
- [Singles](#singles)
- [Studio](#studio)
- [Style](#style)
- [Wiki](#wiki)
- [Work](#work)

### Bio
Public profiles and biographies. 

### Blog
Personal and professional blogging.

### Community
Connecting and engaging with users.

### Contact
Beacons, contact forms, and messaging.

### Dev
Development and debugging tools.

### Express
1-click installations and quick-starts.

### Health
Checks and monitoring.

### Legal
Compliance and regulatory resources.

### Link
Shortlinks, bookmarks, and redirects.

### Marketing
Campaigns and promotions experiences.

### Media
Uploading and managing media.

### One
Identity and access management.

### Pub
Publishing and content management.

### REST
Extending the WordPress REST API.

### Run
Commands and scripts.

### Sh
Server and hosting management.

### Shop
Commerce and payment processing.

### Singles
Registration of singular resources.

### Studio
Cloud-based development environment for experiences.

### Style
Customization and branding.

### Wiki
Documentation and knowledge management.

### Work
Integrating collaboration and workspace tools.

## Extensions

### Altis Accelerate

### Blockstudio

### Co-Author Plus

### Greenshift

### ShopWP

### Slim SEO

### WS Form
### 1. Generators
The `Generators` directory contains logic to generate specific WordPress components. These include:
- **Connections**: Handles integrations with APIs, such as OAuth2 or HeaderBearer connections.
- **Extensions**: Generates code for WordPress extensions, e.g., Coda, Expo, or Plasmo.
- **Files**: Manages general file generation tasks.
- **Pages**: Generates WordPress pages.
- **Plugins**: Includes generators for standard plugins, must-use plugins, and extended plugins.
- **Themes**: Contains subgenerators for theme-related elements like templates, template parts, assets, and global styles.

Each subdirectory has its own `Generator` class that encapsulates the logic for creating its respective components.

### 2. Services
The `Services` directory provides shared utility classes for managing common operations, such as:
- File and directory creation.
- Configuration handling.

### 3. Templates
The `Templates` directory contains predefined template files (e.g., `.tpl` files) used by the generators. Each template serves as a blueprint for the generated code, enabling rapid development and standardization.

### 4. Utils
The `Utils` directory contains utility classes for logging, validation, and other reusable tasks that support the scaffolding logic.

## Usage

The `Scaffold` namespace is designed to be used in conjunction with the WP2 CLI or other build tools. You can leverage the provided classes to quickly scaffold new components by defining inputs and running the appropriate generator.

### Example

To generate a new theme:
1. Use the `ThemeGenerator` class under `Scaffold\Generators\Themes`.
2. Specify the required configuration (e.g., theme name, assets).
3. The generator will create the necessary files and directories based on the provided input.

## Contribution

Contributions to the `Scaffold` directory should:
- Follow PSR-4 autoloading standards.
- Ensure proper separation of concerns.
- Include test coverage under the `tests` directory.

Refer to the root-level `CONTRIBUTING.md` for more details on how to contribute.
<?php
// File: src/Utilities/ManifestUtility.php

namespace MustUse\MUSE\Utilities;

class ManifestUtilities
{

    public function __construct()
    {
        muse_qm_log('MUSE Manifest Utilities initialized');
    }

    /**
     * Save the manifest to a JSON file in the same directory as the calling script.
     *
     * @param array  $manifest The manifest data.
     * @param string $filename The name of the file to create (without .json extension).
     */
    public static function saveManifest(array $manifest, $filename = 'manifest')
    {
        // Determine the directory of the calling script
        $backtrace = debug_backtrace();
        $callingFile = $backtrace[0]['file'];
        $directory = dirname($callingFile);

        // Create the full path for the manifest file
        $filePath = $filename . '.json';

        // Convert the manifest array to JSON format
        $json = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        // Write the JSON to the file
        file_put_contents($filePath, $json);
    }
}
# Install OpenCart Extensions with Composer

## Publish for Composer

### Configuration Extras

|Variable|Description|
|---|---|
|`src-dir`|Base directory of the source extension files, withing the composer package|
|`mappings`|Each file which needs to be managed (install, update, uninstall) by composer|
|`installers`|OpenCart Extensions can have installers: php or xml currently supported (sql not yet)|

### Example

    ```
    {
        "name": "vendor/project-name",
        "type": "opencart-extension",
        "extra": {
            "src-dir" : "src/upload",
            "mappings" : [
                "catalog/controller/vendor/controller_name.php",
                "catalog/model/vendor/other_model.php"
            ],
           "installers" : {
                "php" : "src/install.php" ,
                "xml" : "src/install.xml"
           }
        },
        "require": {
            "beyondit/opencart-extension-installer": "*"
        }
    }
    ```

## Composer Root Project Extras

|Variable|Description|
|---|---|
|`opencart-dir`|set the OpenCart directory in which the extension should be installed|

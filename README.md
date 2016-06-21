# Install OpenCart Extensions with Composer

## Publish for Composer

### Configuration Extras

|Variable|Description|
|---|---|
|`src-dir`|Base directory of the source extension files, withing the composer package|
|`mappings`|Each file which needs to be managed (install, update, uninstall) by composer|

### Example

    ```json
    {
        "name": "vendor/project-name",
        "type": "opencart-extension",
        "extras": {
            "src-dir" : "src/upload",
            "mappings" : {
                "catalog/controller/vendor/controller_name.php",
                "catalog/model/vendor/other_model.php"
            }       
        }
    }
    ```

## Composer Root Project Extras

|Variable|Description|
|---|---|
|`opencart-dir`|set the OpenCart directory in which the extension should be installed|

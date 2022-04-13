# Example Plugin with Wp-Trait

### How To Create plugin With example?

1) Download and copy `plugin-slug` folder in your wordpress plugins dir (wp-content/plugins/..).
2) Change `plugin-slug` dir name and `plugin-slug.php` file to your plugin slug e.g. `wp-user-phone`
3) Change Class Name in Your plugin main file from `PLUGIN_SLUG` to Custom Class name e.g. `WP_USER_PHONE`.
4) Change namespane PSR-4 in composer.json file e.g. `WP_USER_PHONE`
5) Run Command:

```
composer update
```

Now enjoy WordPress development :)




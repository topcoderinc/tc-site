# Topcoder.com Wordpress site #

## File Organization ##

Try to keep php functions in a purposeful location in side the lib/ directory.

All php templat should call get_header();  If using a custom header be sure that header includes main herder at the to
of the template file using the following code.  See header-challenge.php for an example.

    <?php
    get_template_part('header-main');
    ?>

Please be aware of duplicate code.  The use of templates is a key aspect of Wordpress.  Please see
http://codex.wordpress.org/Function_Reference/get_template_part for more information.

## Javascript and CSS ##

Please keep javascript and css out of the php template files.  All javascript should be contained in a javascript file.
Feel free to have as many files as needed for code organization as files are concatenated and minified on production.

The only accept js inside of a php template is assigning javascript variable form a php variable.

### Adding new Javascript and CSS files ###

Any new javascript of CSS files must be registered in the file config/script-register.json or it will not be included on the
page or when minified.

### Minification and CDN ###

There are new theme options to control JS/CSS optimisations.  The options can be found at /wp-admin/themes.php?page=options.php

* __Use CDN__:  This will change the base url for JS and CSS files to the url entered in the "CDN Base URL" setting.
  If CDN is being used it's assumed that JS/CSS is also minified.

  **Path**: "{$base_path}/{version}/{$type}/{$asset_name}.min.{$type}";

* __Use Minified JS/CSS__:  This will use the minified version of the CSS and JS which is assumed to be under the dist
  folder.  Minification can be used without the CDN.  It's assumed you are using the grunt file in this repo to build
  the CSS/JS minification which put the files in the dist folder.

Files are minified using the included grunt file.  Our build scripts minified and send teh files to CloudFront CDN.

### Script and Style Registry ###

The script and style register is a json file which includes all the scripts and styles to be included on any page.
The registry allows us to have different groups of js and css files included with different templates.  Any new js
or css file must be included in the registry.

The json object has two major properties:  packages and templates.

__packages__:  These are groups of files that work together
__templates__:  An list of Wordpress templates that do now use the "default" package.

#### Example: ####

__Loading home page__:

1. Template used: front.php
2. The script loader will look at the script register and see that front.php is not included the template object.
3. The script loader will use the default package to load the js and css.
4. If minifized, then load the file which corresponds to the package name, otherwise load all files in the array.

For more information see lib/scripts.php

## Environmental Variables ##

There is the ability to set variables or settings per envirnoment.  Just add a file called "env.php" in the
theme's config directory.  I recommend added the following two lines to local development.

    <?php
    define("WP_SITEURL", "http://tcqa1.topcoder.com");
    define("WP_HOME", "http://tcqa1.topcoder.com");
    ?>

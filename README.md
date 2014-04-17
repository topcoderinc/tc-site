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

## Local Env Setup ##

When working on the site locally there are a few setup steps.

* The domain must be *.topcoder.com.
* Your local webserver must be using port 80.
* Add a env.php file to the wpcontent/themes/tc-responsive/config directory.  See below for what to put in this file.
* The data export is kept at https://github.com/topcoderinc/tc-site-data.  These are updated weekly upon releases to production.

## Environmental Variables ##

There is the ability to set variables or settings per environment.  Just add a file called "env.php" in the
theme's config directory.  I recommend added the following lines for local development.

    <?php
    define("WP_SITEURL", "http://local.topcoder.com");
    define("WP_HOME", "http://local.topcoder.com");
    
    force_ssl_admin(false);
    force_ssl_login(false);
    ?>

## GIT Information ##

### Branch Model ###

To contribute to this repository, you must follow our branch model – *even if* you're contributing from a fork
of the repo. A branch for a given feature takes the name format `<developer name>-<issue or feature ID>-<description>`.
This branch should be off of the tc-site `dev` branch. When you are done committing your changes to this branch,
submit a pull request against our dev branch.

Every Friday (sometimes postponed until Sunday), the `dev` branch gets pushed to our QA server at
[http://tcqa1.topcoder.com](http://tcqa1.topcoder.com). If you need to make changes to your code that is
already in QA, you will need to do a pull against the most recent release branch, which will look like
`TC-<date>`. The branch you use for these changes should have the same format as usual, but something like
`-fix` should be appended to the end.

### Challenge Submission and Review ###

Registrants in a challenge should work off GIT.  Challenge submissions should be in the form of a git patch.  We will
follow the [https://drupal.org](Drupal) methodology for patches.  This description of a patch was taken from drupal.org

> Patches are pieces of code that solve an existing issue. In fact, patches describe the changes between a before and
after state of either a module or core. By applying the patch the issue should no longer exist.

There is extensive documentation about how to create and apply patches. There are instructions https://drupal.org/patch

* Submitters should review the documentation on creating a patch:  https://drupal.org/node/707484
* Reviewers should review the documentation on applying a patch:  https://drupal.org/node/1399218

__Final Submissions should be in the form of a pull request.  See the section on Branch Model for more information__

### Recommend Workflow for Working on a Challenge ###

These are some workflows to help you get starting with Git.  Once you become more familiar, you can modify the workflow
to fit your style.

**Working on a challenget**

1. Clone the repository. `git clone https://github.com/topcoderinc/tc-site.git dev`
    * If you already have a repository setup, be sure to pull down the latest changes: `git pull origin dev`
1. Create a new branch: `git checkout -b <branch_name>`
1. Make your changes, add new files, etc.
1. Make commits as you go on your branch
1. If you create new files be sure to add them to git first `git add <file name>`

**Submitting a patch to a challenge**

1. Pull down the latest code: `git fetch origin`
1. Merge in the latest changes from dev branch: `git merge dev`
1. Create a patch against the dev branch:  `git diff dev > patchn_ame.patch`
1. Submit the patch to the challenge

**Creating a pull request for for final approval**

1. Create a fork of the project on GitHub if you haven't already. https://github.com/topcoderinc/tc-site
1. Add the new remote to your local git repo.  `git remote add <remote_name> <remote_url>` for example
`git remote add mine git@github.com:indytechcook/tc-site.git`.
1. Make sure all of the code is committed to the branch you were working on above.
1. Verify code is ready to be pushed by running `git status`
1. Push code to remote repository. `git push <remote_name> <branch_name>`
1. Create a pull request from your branch against the dev branch on the main repository.

**More Git help**

More Git help can be found on several places online including the following:

* https://help.github.com/
* http://www.git-scm.com/book

If you prefer to use a GUI, I recommend using SourceTree.  SourceTree is available on Mac and Windows.  http://www.sourcetreeapp.com/

# Blackfire.io: Revealing Performance Secrets with Profiling

Well hi there! This repository holds the code and script
for the [Blackfire.io: Revealing Performance Secrets with Profiling](https://symfonycasts.com/screencast/blackfire) course on SymfonyCasts.

## Getting Started
To get it working, pour some coffee or tea, and follow these steps:
1. run `git clone git@github.com:platformsh-templates/bigfoot-workshop.git`
1. run `cd bigfoot-workshop`
1. run `git checkout workshop`
1. run `cp .env .env.local`
1. run `ddev composer install`
1. run `ddev php bin/console doctrine:schema:update --force`
1. run `ddev php bin/console doctrine:fixtures:load -e dev -n`
1. run `ddev launch`

## Blackfire
Make sure you have access to: <BR>
https://blackfire.io/envs/7455801e-cfc2-4c54-aec8-b72f1d02f2e9/profiles <BR>
If not, DM your favorite trainer with the email address associated with your Blackfire account.
<br>
<br>Then, set up Blackfire’s credentials: <br>
https://blackfire.io/docs/integrations/paas/ddev <br>
Make sure to select the workshop environment to have the correct credentials auto-filled.<br>


## Get Started with DDEV - DDEV Docs
Do local website development on your computer or in the cloud with DDEV.
ddev.readthedocs.ioddev.readthedocs.io


[comment]: <> (------------ TO BE REMOVE -------------)

[comment]: <> (**Use Composer**)

[comment]: <> (Make sure you have [Composer installed]&#40;https://getcomposer.org/download/&#41;)


[comment]: <> (<details>)

[comment]: <> (<summary>Using Symfony server</summary>)

[comment]: <> (<!-- <blockquote>)

[comment]: <> (<br/> -->)

[comment]: <> (**Download Composer dependencies**)

[comment]: <> (```)

[comment]: <> (composer install)

[comment]: <> (```)

[comment]: <> (You may alternatively need to run `php composer.phar install`, depending)

[comment]: <> (on how you installed Composer.)


[comment]: <> (**Configure the .env &#40;or .env.local&#41; File**)

[comment]: <> (Open the `.env` file and make any adjustments you need - specifically)

[comment]: <> (`DATABASE_URL`. Or, if you want, you can create a `.env.local` file)

[comment]: <> (and *override* any configuration you need there &#40;instead of changing)

[comment]: <> (`.env` directly&#41;.)

[comment]: <> (> **Note:**)

[comment]: <> (>)

[comment]: <> (> if you don't have PostgreSQL installed locally, you can use provided PostgreSQL container)

[comment]: <> (> by running command )

[comment]: <> (> ```)

[comment]: <> (> docker-compose up -d )

[comment]: <> (> ```)

[comment]: <> (> then configure your .env `DATABASE_URL` with )

[comment]: <> (> ```)

[comment]: <> (> DATABASE_HOST=127.0.0.1)

[comment]: <> (> DATABASE_PORT=5432)

[comment]: <> (> DATABASE_NAME=app)

[comment]: <> (> DATABASE_USER=symfony)

[comment]: <> (> DATABASE_PASSWORD=ChangeMe)

[comment]: <> (> DATABASE_URL="postgresql://${DATABASE_USER}:${DATABASE_PASSWORD}@${DATABASE_HOST}:${DATABASE_PORT}/${DATABASE_NAME}?serverVersion=13&charset=utf8")

[comment]: <> (> ```)

[comment]: <> (**Set up the Database**)

[comment]: <> (Again, make sure `.env` is set up for your computer. Then, create)

[comment]: <> (the database & tables!)

[comment]: <> (```)

[comment]: <> (php bin/console doctrine:database:create)

[comment]: <> (php bin/console doctrine:migrations:migrate)

[comment]: <> (php bin/console doctrine:fixtures:load)

[comment]: <> (```)

[comment]: <> (If you get an error that the database exists, that should)

[comment]: <> (be ok. But if you have problems, completely drop the)

[comment]: <> (database &#40;`doctrine:database:drop --force`&#41; and try again.)

[comment]: <> (**Start the development web server**)

[comment]: <> (You can use Nginx or Apache, but Symfony's local web server)

[comment]: <> (works even better.)

[comment]: <> (To install the Symfony local web server, follow)

[comment]: <> ("Downloading the Symfony client" instructions found)

[comment]: <> (here: https://symfony.com/download - you only need to do this)

[comment]: <> (once on your system.)

[comment]: <> (Then, to start the web server, open a terminal, move into the)

[comment]: <> (project, and run:)

[comment]: <> (```)

[comment]: <> (symfony serve)

[comment]: <> (```)

[comment]: <> (&#40;If this is your first time using this command, you may see an)

[comment]: <> (error that you need to run `symfony server:ca:install` first&#41;.)

[comment]: <> (Now check out the site at `https://localhost:8000`)

[comment]: <> (</details>)

[comment]: <> (<details>)

[comment]: <> (<summary>Using DDev from scratch</summary>)

[comment]: <> (Ddev provides an integration with Platform.sh that makes it simple to develop Symfony locally. )

[comment]: <> (Check the [providers documentation]&#40;https://ddev.readthedocs.io/en/latest/users/providers/platform/&#41; for the most up-to-date information.)

[comment]: <> (Steps are as follows:)

[comment]: <> (1. run `git clone git@github.com:platformsh-templates/sfcon2022-symfony-bigfoot-workshop.git sfcon-bigfoot-workshop`)

[comment]: <> (1. run `symfony composer install`)

[comment]: <> (1. run `symfony project:init`)

[comment]: <> (1. run `git add .platform.app.yaml .platform/services.yaml .platform/routes.yaml && git commit -m "Add Platform.sh configuration"`)

[comment]: <> (1. run `symfony cloud:create`)

[comment]: <> (   1. _Login via browser: yes_)

[comment]: <> (   1. _Choose your organization_)

[comment]: <> (   1. _Choose project title: SFCon2022 - Symfony Bigfoot workshop_)

[comment]: <> (   1. _Choose your region: [fr-3.platform.sh] Gravelines, France &#40;OVH&#41; [58 gC02eq/kWh]_)

[comment]: <> (   1. _Choose plan : 0 &#40;Developpement&#41;_)

[comment]: <> (   1. _Choose number of &#40;active&#41; environments &#40;default 3&#41;_)

[comment]: <> (   1. _Choose storage &#40;default 5GiB&#41;_)

[comment]: <> (   1. _Choose default branch &#40;default “main”&#41; : main_)

[comment]: <> (   1. _Set the new project "SFCon2022 - Symfony Bigfoot workshop" as the remote for this repository?: y_)

[comment]: <> (   1. _Given price is an estimation after the free trial period: you can continue_)

[comment]: <> (1. run `symfony deploy`)

[comment]: <> (1. Initialize data on Platform.sh project)

[comment]: <> (   1. run `symfony ssh`)

[comment]: <> (   1. [option] run `$ php bin/console doctrine:schema:update --dump-sql`)

[comment]: <> (   1. run `php bin/console doctrine:schema:update --force`)

[comment]: <> (   1. run `php bin/console doctrine:fixtures:load -e dev`   )

[comment]: <> (   1. `exit` from Platform.sh container)

[comment]: <> (1. [Install ddev]&#40;https://ddev.readthedocs.io/en/stable/#installation&#41;.)

[comment]: <> (1. run `ddev config`)

[comment]: <> (    1. _Project name &#40;sfcon-bigfoot-workshop&#41;: \<enter\>_)

[comment]: <> (    1. _Docroot Location &#40;\_www&#41;: public_)

[comment]: <> (    1. _Project Type [backdrop, craftcms, drupal10, drupal6, drupal7, drupal8, drupal9, laravel, magento, magento2, php, shopware6, typo3, wordpress] &#40;php&#41;: \<enter\>_)

[comment]: <> (1. Check that library `jq` is installed locally)

[comment]: <> (    1. Mac: `brew list | grep jq`  → jq)

[comment]: <> (    1. Windows: `winget list -q jq`)

[comment]: <> (    1. If not, install it)

[comment]: <> (        1. Mac : `brew install jq`)

[comment]: <> (        1. Windows: `chocolatey install jq`)

[comment]: <> (1. Create a <a href="https://docs.platform.sh/administration/cli/api-tokens.html#get-a-token" target="_blank">Platform.sh API Token</a> and keep it)

[comment]: <> (1. run `ddev get platformsh/ddev-platformsh` _&#40;this will get copy production configs to setup Ddev container&#41;_)

[comment]: <> (    1. _Please enter your platform.sh token: \<Platform.sh APIToken\>_)

[comment]: <> (    1. _Please enter your platform.sh project ID &#40;like '6k4ypl5iendqd'&#41;: \<projectID\>_)

[comment]: <> (    1. _Please enter your platform.sh project environment &#40;like 'main'&#41;: main_)

[comment]: <> (1. run `ddev pull platform` _&#40;this will pull data from Platform.sh project&#41;_)

[comment]: <> (    1. _https://ddev.readthedocs.io/en/stable/users/details/opting-in)

[comment]: <> (       Permission to beam up? [Y/n] &#40;yes&#41;: \<enter\>_)

[comment]: <> (1. Go on <a href="https://sfcon-bigfoot1-workshop.ddev.site/" target="_blank">https://sfcon-bigfoot1-workshop.ddev.site/</a>)

[comment]: <> (1. When you have finished with your work, run `ddev stop` and `ddev poweroff`.)

[comment]: <> (> **Note:**)

[comment]: <> (>)

[comment]: <> (> PHP 8.1 is needed when using latest 6.x version of this project.<br>)

[comment]: <> (> So please change/check ddev .ddev/config.platformsh.yaml file and use PHP version 8.1 or higher <br>)

[comment]: <> (> ```)

[comment]: <> (> // .ddev/config.platformsh.yaml)

[comment]: <> (> php_version: "8.1")

[comment]: <> (> ```)

[comment]: <> (> Then use `ddev restart`)

[comment]: <> (</details>)



[comment]: <> (<details>)

[comment]: <> (<summary>Using DDEV with an existing Bigfoot project deployed on Platform.sh</summary>)

[comment]: <> (Ddev provides an integration with Platform.sh that makes it simple to develop Symfony locally. )

[comment]: <> (Check the [providers documentation]&#40;https://ddev.readthedocs.io/en/latest/users/providers/platform/&#41; for the most up-to-date information.)

[comment]: <> (Steps are as follows:)

[comment]: <> (1. run `symfony get <projectID>`)

[comment]: <> (1. run `symfony composer install`)

[comment]: <> (1. [Install ddev]&#40;https://ddev.readthedocs.io/en/stable/#installation&#41;.)

[comment]: <> (1. run `ddev config`)

[comment]: <> (   1. _Project name &#40;sfcon-bigfoot1-workshop&#41;: \<enter\>_)

[comment]: <> (   1. _Docroot Location &#40;\_www&#41;: public_ )

[comment]: <> (   1. _Project Type [backdrop, craftcms, drupal10, drupal6, drupal7, drupal8, drupal9, laravel, magento, magento2, php, shopware6, typo3, wordpress] &#40;php&#41;: \<enter\>_)

[comment]: <> (1. Check that library `jq` is installed locally)

[comment]: <> (   1. Mac: `brew list | grep jq`  → jq)

[comment]: <> (   1. Windows: `winget list -q jq`)

[comment]: <> (   1. If not, install it)

[comment]: <> (        1. Mac : `brew install jq`)

[comment]: <> (        1. Windows: `chocolatey install jq`)

[comment]: <> (1. Create a <a href="https://docs.platform.sh/administration/cli/api-tokens.html#get-a-token" target="_blank">Platform.sh API Token</a> and keep it)

[comment]: <> (1. run `ddev get platformsh/ddev-platformsh` _&#40;this will get copy production configs to setup Ddev container&#41;_)

[comment]: <> (    1. _Please enter your platform.sh token: \<Platform.sh APIToken\>_)

[comment]: <> (    1. _Please enter your platform.sh project ID &#40;like '6k4ypl5iendqd'&#41;: \<projectID\>_)

[comment]: <> (    1. _Please enter your platform.sh project environment &#40;like 'main'&#41;: main_)

[comment]: <> (1. run `ddev pull platform` _&#40;this will pull data from Platform.sh project&#41;_)

[comment]: <> (   1. _https://ddev.readthedocs.io/en/stable/users/details/opting-in)

[comment]: <> (       Permission to beam up? [Y/n] &#40;yes&#41;: \<enter\>_)

[comment]: <> (1. Go on <a href="https://sfcon-bigfoot1-workshop.ddev.site/" target="_blank">https://sfcon-bigfoot1-workshop.ddev.site/</a>)

[comment]: <> (1. When you have finished with your work, run `ddev stop` and `ddev poweroff`.)

[comment]: <> (> **Note:**)

[comment]: <> (>)

[comment]: <> (> PHP 8.1 is needed when using latest 6.x version of this project.<br>)

[comment]: <> (> So please change/check ddev .ddev/config.platformsh.yaml file and use PHP version 8.1 or higher <br>)

[comment]: <> (> ```)

[comment]: <> (> // .ddev/config.platformsh.yaml)

[comment]: <> (> php_version: "8.1")

[comment]: <> (> ```)

[comment]: <> (> Then use `ddev restart`)

[comment]: <> (</details>)

Have fun!

## Have Ideas, Feedback or an Issue?

If you have suggestions or questions, please feel free to
open an issue on this repository or comment on the course
itself. We're watching both :).

## Thanks!

And as always, thanks so much for your support and letting
us do what we love!

<3 Your DevRel friends at Platform.sh 

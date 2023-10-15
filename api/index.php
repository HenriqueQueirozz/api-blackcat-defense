
Kenean
Kenean

Posted on 9 de out. de 2021 • Updated on 30 de nov. de 2021
22 5
Free Serverless Laravel Deployment
#laravel
#serverless

Free Serverless Laravel Deployment

If you have a small Laravel application and you don't want to go through the trouble of setting your own VPS or something like Laravel Vapor you might want to read this one through.

So in this ride we will see how we can set up and deploy our Laravel application on a serverless environment called Vercel, including automated deployments and previews with out any cost.

At this point you are probably thinking this sound too good to be true. Well... you are right, there are of course some gotchas.

The primary thing is you have to find other ways to host and manage your database and other services your application need.

And this set up would not be a replacement for the more robust deployment strategies on AWS and Laravel Vapor.

Now we got that out of the way, Lets dig in!
Creating a Laravel application

If you already have a project you can skip this step. I will go ahead and create a new one using composer for the sake of clarity.

We will call our app laravel-vercel

Open your terminal and run:
composer create-project laravel/laravel laravel-vercel
Initialize a Git repository and push to Github

After the application is created, we will need to go into the root directory and set up Git and push our code to Github.

Run these commands to set up a Git repository:
cd laravel-vercel
git init
git add .
git commit -m "init"

Now lets create a new repository on Github and push our application.

Go to the Github repository creation page here and create a repository called laravel-vercel.

screencapture-github-com-new-1633704211118

After we create our repository we will see a page similar to the following image.
screencapture-github-com-kenean-50-laravel-vercel-1633704702417

Copy and run the commands highlighted in the image from your newly create Github repository page.
Create Vercel configuration files

So for this magic to happen we have to create 2 Vercel configuration files in our application.
app/index.php

Create a php file called index.php within a folder called api in your application root directory.
And then add the following content to it.

<?php
require __DIR__ . '/../public/index.php';

The default entry point for the serverless function on vercel is the app directory. the above script will make sure the request is forwarded to our Laravel app entry point public/index.php.
vercel.json

Create a new file again called vercel.json in the root directory. This file will be responsible for setting up our Vercel configurations. This is where the real magic happens.

Add the following content to your file and we will go through them in details.

{
    "version": 2,
    "functions": {
        "api/index.php": { "runtime": "vercel-php@0.3.1" }
    },
    "routes": [
        {
            "src": "/(.*)",
            "dest": "/api/index.php"
        }
    ],
    "env": {
        "APP_NAME": "Laravel Vercel",
        "APP_ENV": "production",
        "APP_DEBUG": "false",
        "APP_URL": "https://laravel-vercel.vercel.app",

        "APP_CONFIG_CACHE": "/tmp/config.php",
        "APP_EVENTS_CACHE": "/tmp/events.php",
        "APP_PACKAGES_CACHE": "/tmp/packages.php",
        "APP_ROUTES_CACHE": "/tmp/routes.php",
        "APP_SERVICES_CACHE": "/tmp/services.php",
        "VIEW_COMPILED_PATH": "/tmp",
        "CACHE_DRIVER": "array",
        "LOG_CHANNEL": "stderr",
        "SESSION_DRIVER": "cookie",
        "VIEW_COMPILED_PATH": "/tmp/views",
        "SSR_TEMP_PATH": "/tmp/ssr",
        "NODE_PATH": "node"
    }
}

version

"version": 2, 
//This make sure to run our code on Vercel version 2

functions

"functions": {
    "api/index.php": { "runtime": "vercel-php@0.3.1" }
}, 

The api/index.php key will define our app entry point which will be forwarded to the Laravel app entry point public/index.php

"runtime": "vercel-php@0.3.1" will define our PHP runtime, which is basically a module that transform our code into Serverless Functions for Vercel.

In this set up we are using the community runtime for PHP called vercel-php.
routes

"routes": [
   {
      "src": "/(.*)",
      "dest": "/api/index.php"
   }
],

The routes will tell Vercel to forward all URIs to our application serverless function.
env

"env": {
        "APP_NAME": "Laravel Vercel",
        "APP_ENV": "production",
        "APP_DEBUG": "false",
        "APP_URL": "https://laravel-vercel.vercel.app",

        "APP_CONFIG_CACHE": "/tmp/config.php",
        "APP_EVENTS_CACHE": "/tmp/events.php",
        "APP_PACKAGES_CACHE": "/tmp/packages.php",
        "APP_ROUTES_CACHE": "/tmp/routes.php",
        "APP_SERVICES_CACHE": "/tmp/services.php",
        "VIEW_COMPILED_PATH": "/tmp",
        "CACHE_DRIVER": "array",
        "LOG_CHANNEL": "stderr",
        "SESSION_DRIVER": "cookie",
        "VIEW_COMPILED_PATH": "/tmp/views",
        "SSR_TEMP_PATH": "/tmp/ssr",
        "NODE_PATH": "node"
    }

As you already probably guessed this is going to be our application environment variable section. you can pass environment variables you have on your Laravel app .env.

NOTICE! do not pass sensitive environment values like APP_KEY and database config values since they will be tracked by git and would be visible on your repo. We later will see how we can pass those values in more secure way.

Some of the unusual variables are Laravel's default values that we don't normally see in the our env file. we have to pass them here since serverless applications are state-less and the only folder we can reliably modify at run-time is Vercel’s tmp folder.
Commit and push Changes to Github

lets push our newly created file to our Github repo

git add .
git commit -m "add Vercel configs"
git push orgin Main
Link our repository to Vercel

Create your free account on Vercel using your Github account.

Once you are done, on your Vercel Dashboard you will see a button called New Project, something similar this the following image.
screencapture-vercel-com-dashboard-1633776362674

When you click on it you see the creation page and your Github repositories listed, on a much similar page like the following.

screencapture-vercel-com-new-1633777753834

Click Import on your laravel-vercel repository (or what ever you named yours).

If you are not seeing your repositories listed, click on the Adjust GitHub App Permissions link and set up the permissions there.

Once you click on import you will see the following like page
Screenshot from 2021-10-09 15-00-52

You can click on skip for the Create a Team section and go to the Configure Project section.

Screenshot from 2021-10-09 15-01-51

On the Environment Variable section, we will add our sensitive Laravel app env variables.
These are:
APP_KEY
DB_CONNECTION
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD

Finally, click on Deploy and sit back to see the magic happen.

Your application will get built and deployed on Vercel. and you will see the following success page with a Vercel domain name to your application.

screencapture-vercel-com-kenean-50-laravel-vercel-1633782165938

Now if you have new changes to be deployed all you have to do is commit and push your changes to your Github repository Main branch and it will automatically get built and deployed to your server, how cool is that!

If you want to change the domain name to your app you can go to the settings tab on Vercel and there is a whole page to set up and manage your domain.

Peace out ✌️
Top comments (7)
pic
 
beaumontyun profile image
•
8 de jan. de 22

FYI - if anyone is getting error 500 after you deployed, make sure the “runtime: “vercel-php” version is up-to-date by checking their Github. The current version I used as of Jan 2022 is 0.4.0.
Reply
 
jereconjota profile image
•
1 de nov. de 21

Nice! but where can I host only my database?
Reply
 
atiq profile image
•
7 de jun. de 22

You can host the database using Devrims Cloud hosting platform. The platform allows you to install any database services like MongoDB, MySQL, MariaDB, ElasticSearch etc and create multiple databases in a few clicks. You can easily connect the database services with external resources via allowing the IP in the security section of the Devrims platform.
Blazing Fast Laravel Hosting On Cloud Server - Devrims

To get 14x Turbo Speed, host your Laravel on a dedicated cloud server optimized for Laravel Hosting with SSD Storage, Security, Fast Network and 24*7 Support.

  <div class="color-secondary fs-s flex items-center">
      <img
        alt="favicon"
        class="c-embed__favicon m-0 mr-2 radius-0"
        src="https://devrims.com/wp-content/uploads/2021/08/cropped-fav-icon-32x32.png"
        loading="lazy" />
    devrims.com
  </div>
</div>




Devrims also provide Managed Laravel Hosting services with free website migration.
Reply
 
kenean50 profile image
•
2 de nov. de 21

You can use RDS from AWS or set up your own Mysql server on a VPS
Reply
 
bobwatcherx profile image
•
10 de jun.

why i run command vercel. or upload from github and error like this

No Output Directory named "dist" found after the Build completed. You can configure the Output Directory in your Project Settings.
Reply
 
mellunar profile image
•
13 de jul.

Set the output folder to api
Reply
 
gude1 profile image
•
17 de jul. • Edited on 17 de jul.

Create a empty dist folder in your root directory will solve ths issue
Reply
Code of Conduct • Report abuse
profile
Sentry
Promoted

Laravel Sentry
Laravel Error Monitoring with Complete Stack Traces 🚫
<?php
require __DIR__ . '/../public/index.php';
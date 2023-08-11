# Overview

QuickieDox is a **purely vanilla PHP**( >= 7.4) project that allows you to quickly create elegant documentation from markdown files. Visit [https://quickiedox.com](https://quickiedox.com) for the full documentation. 

QuickieDox is not software as a service, thus, doesn't host any documentation. You will need to host everything yourself just like you would host any of your websites.

&nbsp;
## Who Can Use QuickieDox

- To use QuickieDox you must already know how to write markdown syntax and also know some basic PHP. [This guide](https://www.markdownguide.org/getting-started/) can bring you up to speed on writing markdown.

- Anyone with documentation needs regardless of the size can use QuickieDox

&nbsp;
## Installation

There are two ways to create your own documentation website using QuickieDox.

### Via Composer

This option is the easiest. Simply run the command below.

```bash
# replace your-project-name with the name of the directory you want created
# or the name of your project (directory will be created if it does not exist)
```
```bash
composer create-project mkocansey/quickiedox your-project-name
```
### Clone From GitHub: HTTPS
```bash
# create a directory where you want to clone quickiedox
# cd into that directory and run 
```
```bash
git clone https://github.com/mkocansey/quickiedox.git
```

### Clone From GitHub: SSH
```bash
# create a directory where you want to clone quickiedox
# cd into that directory and run 
```
```bash
git clone git@github.com:mkocansey/quickiedox.git
```

&nbsp;
## Rename .env-example

You will find a `.env-example` file at the root of the project you just cloned. Rename it to `.env`. The app will not run if this is not done.

```bash
mv .env-example .env
```

&nbsp;

## Run the App

Now that you have cloned the repo, let us run the app to ensure you can see the documentation that
ships by default. From the root of the directory you just created, run:

```bash
composer install
```

Let's start an inbuilt PHP server to quickly test. Still at the root of the project, type

```bash
php -S localhost:8000
```
The above ccommand assumes port 8000 is not in use by another site or app.
You should see output similar to what is below. The version of PHP might differ depending on what you have installed on your machine.

```bash
[Thu Mar  9 00:11:36 2023] PHP 8.2.3 Development Server (http://localhost:8000) started
```

Navigating to [http://localhost:8000](http://localhost:8000) should display the screen below.

&nbsp;


![QuickieDox Homepage](https://quickiedox.com/assets/images/homepage.jpg)

&nbsp;

Clicking on **Read Documentation** from the home page as shown in the image above should display the screen below.

![QuickieDox Homepage](https://quickiedox.com/assets/images/installation.jpg)

&nbsp;


You can tell from the above image that QuickieDox is unable to load the documentation navigation or the default documentation pages. This is very much expected since **we have not pulled in the markdown files** that make up the documentation.

By default QuickieDox expects the markdown files to be in the `markdown` directory at the root of the project you just created. You can use any directory name of your choice but just make sure you update the [config file](https://quickiedox.com/docs/main/customize-config) to tell QuickieDox where to load your `.md` files from.

&nbsp;
## Pull In The Markdown Files

The markdown files that make up the documentation are expected to sit in a directory you specify. The default is `markdown`. The [assumption/convention](https://quickiedox.com/docs/main/convention-doc) here is that your markdown files are hosted in their own git repo. There are two ways to pull in the markdown files.

&nbsp;
### Use The Inbuilt Cloning Tool

&nbsp;
#### via HTTP

&nbsp;

It is much easier to pull in your markdown files using the cloning URL that is built into QuickieDox. Assuming you are still running the app from the server we started above using `php -S localhost:8000`, you will need to visit the URL below.

[http://localhost:8000/get-markdown](http://localhost:8000/get-markdown)

Ensure you have properly modified your QuickieDox [configurations](https://quickiedox.com/docs/main/customize-config). The URL will ask for the PIN you defined in the `.env` or `.config.php` file as `GIT_CLONE_PIN`. You won't be able to use this URL if your PIN is blank. 

&nbsp;

#### via The clone.sh File

&nbsp;

Cloning via HTTP will work well if you are cloning from public repositories. If you are cloning your documentation from a private repository, using the `clone.sh` file is a better option. 

Included at the root of the project is a `clone.sh` shell script that pulls in your markdown files from the repo you specify. You will need to edit the file to change the default values defined for `GIT_REPO_URL`, `DOCS_DIRECTORY` and `DOC_VERSIONS `.

```bash
# clone.sh

#!/bin/bash

# update this to your own documentation repo url

GIT_REPO_URL="https://github.com/mkocansey/quickiedox-mds.git"


# update this to the directory you prefer to clone your docs into

DOCS_DIRECTORY="markdown"


# note the versions are not strings
# each version needs to be defined on its own line without quotes

DOC_VERSIONS=(
  main
  #master
  #2.x
  #1.x
)
```

Ensure the script has permissions to execute for owner and/or group. Run.

```bash 
ls -l clone.sh

# you should get an output similar to the line below

-rwxr-xr--@  1 your-username  your-group  1026 Mar 20 11:14 clone.sh
```

Now run this command to execute the shell script to pull in the documentation. 

```bash
./clone.sh
```


&nbsp;
### Copy and Paste

QuickieDox only needs `.md` files in the `markdown` directory. You can simple copy and paste your `.md` files into this directory and voila! If your documentation is in versions, you will need to have a separate directory for every version. For example, if your documentation has versions 1.5 and 2.2, you will need to create `1.5` and `2.2` directories in the `markdown` directory. You will then need to copy the appropriate `.md` files into their respective directories.
See the [Conventions > Versioning docs](https://quickiedox.com/docs/main/convention-versions) for more on this.

&nbsp;

## Further Reading

Visit [https://quickiedox.com](https://quickiedox.com) for the full documentation but these should help you better understand how QuickieDox works.
* [Directory Structure](https://quickiedox.com/docs/main/structure)
* [The Configuration File](https://quickiedox.com/docs/main/customize-config)
* [The Navigation File](https://quickiedox.com/docs/main/customize-nav)
* [Versioning Your ocumentation](https://quickiedox.com/docs/main/convention-versions)
* [Customizing The Homepage](https://quickiedox.com/docs/main/customize-home)
* [How QuickieDox Works](https://quickiedox.com/docs/main/architecture)
* [Deploying Your Docs](https://quickiedox.com/docs/main/structure)

&nbsp;

## Known Issues
* Integration of Algolia
* Faster site-wide search
* Protecting docs that require signiing in before reading
* Top level navigation items can only be parents and not links to pages

&nbsp;

## Support

You can tweet [@quickiedox](https://twitter.com/quickiedox) or email kabutey@gmail.com if you have any questions.

&nbsp;

# PvcCore

## Introduction

ProcessWire is a great CMS/CMF that is comfortable to use and manage. Coders as well as designers especially love the fact that PW gives you a maximum of freedom when it comes to structuring and coding your templates. But also it is this freedom that makes every single one of us come up with her or his completely own style how to deal with files, separation of data, logic and presentation.

PVC stands for Page-View-Controller. Pages in PW are considered data containers which’s structure is defined by templates and field definitions. PVC considers PW’s _pages_ a resource’s data (or kind of _model_) layer and adds a _view_ and a _controller_ layer.

By doing this PVC gives you a clear structure to organize your code. _Controllers_ keep your business logic and let you decorate your page’s data and present it all to the page’s _view_. _Views_ for example let you define helpers to deal with presentation efficiently.

But PVC brings you much more, you’ll love. Like _action routes_ and the possibility to use (and write) template renderers (e.g. for Twig syntax). So dive in and explore!

## Installation
PvcCore installs all necessary files and sets up PVC folder structure in your ```site/templates/``` directory. Also it comes with _PvcRendererNative_, the basic renderer which let you write templates the good old PHP way.

To install PvcCore, copy the Pvc folder to your ```site/modules/``` directory and install the module through the ProcessWire module interface.

After installation you’ll find some new folders in your ```site/templates/``` directory.

### Folders and Files

#### Folder structure

* __assets__ Where your scripts, styles and images are kept
  * __images__
  * __scripts__
  * __styles__
* __controllers__ Controller files go here
* __layouts__ Layout files contain the outer document structure pages share
* __snippets__ Chunks of code you can use within your templates
* __views__ View files and templates go here
  * __home__ Each PW template gets its own view folder to keep a template for each action

#### Base Files and Examples

Within the new folder structure you’ll find a few files.

* ```site/templates/controllers/base.controller.php``` contains the _BaseController_ class. It extends _PvcController_ class. Here you can add general business logic that you want to apply to all the controllers you’ll build. Like defining script or style assets. Make sure to all your future controllers extend _BaseController_.
* ```site/templates/controllers/home.controller.php``` is an example controller which deals with business logic for pages with template _home_.
* ```site/templates/views/base.view.php``` contains the _BaseView_ class. It extends _PvcView_ and if you needed for example some custom view helpers, you’d define them here. Future view classes extend _BaseView_.
* ```site/templates/views/home/index.tmpl.php``` is an action template. As _index_ is the default action of each controller, there has to be a template for it for each PW template.
* ```site/templates/layouts/default.tmpl.php``` is the default layout in which’s outlet your action templates will be rendered.
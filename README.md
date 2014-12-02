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

## Folders and Files

### Folder structure

* __assets__ Where your scripts, styles and images are kept
  * __images__
  * __scripts__
  * __styles__
* __controllers__ Controller files go here
* __layouts__ Layout files contain the outer document structure pages share
* __snippets__ Chunks of code you can use within your templates
* __views__ View files and templates go here
  * __home__ Each PW template gets its own view folder to keep a template for each action

### Base Files and Examples

Within the new folder structure you’ll find a few files.

* ```site/templates/controllers/base.controller.php``` contains the _BaseController_ class. It extends _PvcController_ class. Here you can add general business logic that you want to apply to all the controllers you’ll build. Like defining script or style assets. Make sure to all your future controllers extend _BaseController_.
* ```site/templates/controllers/home.controller.php``` is an example controller which deals with business logic for pages with template _home_.
* ```site/templates/views/base.view.php``` contains the _BaseView_ class. It extends _PvcView_ and if you needed for example some custom view helpers, you’d define them here. Future view classes extend _BaseView_.
* ```site/templates/views/home/index.tmpl.php``` is an action template. As _index_ is the default action of each controller, there has to be a template for it for each PW template.
* ```site/templates/layouts/default.tmpl.php``` is the default layout in which’s outlet your action templates will be rendered.

## How it works
ProcessWire processes a page view through its _ProcessPageView__ module which then calls __render()__ method on the page to be rendered. The __render()__ method itself is a method hook provided to __Page__ objects through the __PageRender__ module. _PVC_ hooks into this process and makes PW initiate a _PvcView_ object (with its corresponding _PvcController_ object attached to it) instead of a _TemplateFile_. From there on _PVC_ takes care of the template processing.

### View
Each PW template gets an individual view class that inherits from _BaseView_ and/or _PvcView_ class. Its name is determined by the template’s name. So template _home_’s view class is named _HomeView_. _PVC_ looks up the class within the views folder. For template _home_ it would look for ```site/templates/views/home.view.php```. If the class can’t be found, it is generated on the fly. View classes are instanciated with a controller passed in.

#### View Helpers
View helpers are functions that are made available as global functions within your action template context. As they are created from closures, they can be allowed to have access to the current scope. They can be quite handy tools to abstract view layer logic.

How are they defined?

How to deal with inheritance?

### Controller
Each view gets an individual controller class that inherits from _BaseController_ and/or _PvcController_ class. Its name is determined by the template’s name. So template _home_’s controller class is named _HomeController_. _PVC_ looks up the class within the controllers folder. For template _home_ it would look for ```site/templates/controllers/home.controller.php```. If the class can’t be found, it is generated on the fly, too.

#### Actions
By default when you access a page via its url, its controller executes the _index_ action. But imagine the case you have an _article_ template and you provide a comment form. Now you can enable urlSegments for this template and define an _action_ named _comment_. To do so you just add a public method to your _ArticleController_ class. It will be executed as soon as you access the page with the segment ```/comment/``` attached to its URL. If you have multiple segments like ```/comment/list/```, the action method would be called _commentList_.

#### Action Routes
More interesting is, that you can wire particular patterns of URL segments to your actions and use dynamic parts as input. For example, if you want an action to be able to delete a comment, you’ll need the comment’s id within the action method.
In _PVC_ this is done via _action routes_ that you can define within your controllers __init()__ method like this:

```php
class ArticleController extends BaseController {

  public function init() {
    $this->route('/delete/:id/', array('id'=>'^[0-9]+$'), 'delete');
  }

}
```
You pass the segment pattern into the controllers _route()_ method as the first argument. Second argument is an associative array that contains a regular expression to validate each dynamic segment. The third argument is the name of the action method you want to call when the route is accessed. All dynamic segments will be extracted and made available within your action method through ```$this->input->route```.

### Action Templates
Each action defined in a controller requires a template to be rendered. Like the _HomeController_’s _index_ action template is found in ```site/templates/views/home/index.tmpl.php```, an action _edit_ would need a template ```site/templates/views/home/edit.tmpl.php```.

### Renderers
Renderers are provided to _PVC_ as separate modules which implement a special interface to interact with the view while preparing rendering process. It’s the idea to allow implementing alternative template syntaxes by installing or developing other renderers.

#### Native Renderer

#### Renderer API
Describe API, give an idea how to implement own renderers.

## The Future
* Maybe encapsulated template outlets to render actions within the template’s _index_ action template?
* More renderers. Which one?

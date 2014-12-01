<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?=$page->title;?></title>
  <meta name="description" content="HTML5 Doc">
  <meta name="author" content="Pvc">
  <meta name="generator" content="Pvc">


  <?=styles('header')?>
  <?=scripts('header')?>
  <!--[if lt IE 9]>
  <?=scripts('ie');?>
  <![endif]-->
</head>
<body>
  <header>
    <h1><?=$title;?></h1>
  </header>
  <main>
  <?=$outlet;?>
  </main>
  <?=scripts('footer');?>
</body>
</html>

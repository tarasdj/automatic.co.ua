<?php  
  include 'mvc/controller/controller.php';
  $controller = new controller;
?>
<!DOCTYPE html>
<html lang="en">
  <?php $controller->header(); ?>
<body>
  <div class="row">
    <?php $controller->getAdminPanel(); ?>
  </div>
  <div class="main-menu">
    <div class="row">   
      <?php $controller->User(); ?>
      <?php $controller->main_menu(); ?>
    </div>
  </div>
  <div class="row">
    <div class="content">
      <?php $controller->routing(); ?>
    </div>
  </div>
</body>
<div class="row">
  <footer>
    <?php $controller->footer(); ?> 
  </footer>
</div>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view($folder_themes . '/commons/meta') ?>
  <?php $this->load->view($folder_themes . '/commons/source_css') ?>
  <?php $this->load->view($folder_themes . '/commons/source_js') ?>
</head>
<body class="font-primary bg-gray-100">
  
  <?php $this->load->view($folder_themes . '/commons/loading_screen') ?>
  <?php $this->load->view($folder_themes . '/commons/header') ?>
  <div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
    <main class="w-full overflow-hidden space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
      <?php
        if ($tampil) {
          $this->load->view($folder_themes . '/partials/kesehatan/index');
        } else {
          theme_view('partials/not_found');
        } ?>
    </main>    
  </div>

  <?php $this->load->view($folder_themes .'/commons/footer') ?>
  <script src="<?= theme_asset("js/script.min.js?" . THEME_VERSION) ?>"></script>

</body>
</html>
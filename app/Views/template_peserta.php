<?php 
echo view('layout/head');

echo '<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show pace-done">';

echo view('layout/head_navbar');

echo '<div class="app-body">';
echo view('layout/sidebar_peserta');
echo view('page/'.$p);
echo '</div>';

echo view('layout/script_bawah');
echo '</body>';



// echo view('layout/head_menu');
// echo view('page/'.$p);

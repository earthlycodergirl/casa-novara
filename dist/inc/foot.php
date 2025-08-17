<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <img src="dist/img/logo-foot.png" alt="Mia Realty" class="img-fluid" />
      </div>

      <div class="col-md-3">
        <div class="foot-links">
          <h6><?= $lan['foot']['h6'][0] ?></h6>
          <a href="listings/cancun/all">Cancun <?= $lan['ho']['real'] ?></a>
          <a href="listings/puerto-morelos/all">Puerto Morelos <?= $lan['ho']['real'] ?></a>
          <a href="listings/playa-del-carmen/all">Playa del Carmen <?= $lan['ho']['real'] ?></a>
          <a href="listings/akumal/all">Akumal <?= $lan['ho']['real'] ?></a>
          <a href="listings/tulum/all">Tulum <?= $lan['ho']['real'] ?></a>
          <a href="listings/cozumel/all">Cozumel <?= $lan['ho']['real'] ?></a>
        </div>
      </div>

      <div class="col-md-3">
        <div class="foot-links">
          <h6><?= $lan['foot']['h6'][1] ?></h6>
          <a href="<?= $link_list[$lang] ?>"><?= $lan['foot']['list'] ?></a>
          <a href="<?= $link_about[$lang] ?>"><?= $lan['foot']['about'] ?></a>
          <a href="<?= $link_contact[$lang] ?>"><?= $lan['foot']['cont'] ?></a>
          <a href="<?= $link_blog[$lang] ?>"><?= $lan['foot']['blog'] ?></a>
        </div>
      </div>

      <div class="col-md-3">
        <div class="social-links">
          <?php if(!empty($social_mia)){ foreach($social_mia as $ii){
            if($ii['icon'] == 'facebook'){
              $so_class = 'yellow-li';
            }else{ $so_class = ''; }
            ?>
            <a href="<?= $ii['val'] ?>" target="_blank" class="<?= $so_class ?>">
              <i class="bi bi-<?= $ii['icon'] ?>"></i>
            </a>
          <?php } } ?>
        </div>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-6">
        <p class="rights"><?= $lan['foot']['copy'] ?></p>
      </div>
      <div class="col-6 text-end">
        <a href="<?= $link_terms[$lang] ?>" target="_blank"><?= $lan['foot']['terms'] ?></a>
        <a href="<?= $link_privacy[$lang] ?>" target="_blank"><?= $lan['foot']['privacy'] ?></a>
      </div>
    </div>
  </div>
</footer>
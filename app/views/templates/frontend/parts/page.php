        <h1 class="text-center">
            <?php echo $data['page-title']; ?>
        </h1>
        <?php foreach(UltraPHP::$views as $view): ?>
            <?php require $view; ?>
        <?php endforeach; ?>

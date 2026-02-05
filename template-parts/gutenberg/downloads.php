<div class="downloads flex flex-wrap gap-4 lg:px-25">
    <?php $downloads = get_field('downloads');
    
    if($downloads) :
        foreach($downloads as $download) :
       
            ?>

<div class="download-item">
    <a href="<?= get_field('download', $download['download']->ID)['url'] ?>" class="btn btn-red flex gap-2" target="_blank" download>
        <?= $download['download']->post_title ?> <svg xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15" fill="none"><path d="M5.39746 12.1455L0.0761716 6.82519L1.14062 5.76074L4.69922 9.32031L4.69922 -2.63079e-07L6.2041 -1.97299e-07L6.2041 9.21191L9.6543 5.76074L10.7178 6.8252L5.39746 12.1455Z" fill="white"/><rect y="13.4952" width="10.6409" height="1.50488" fill="white"/></svg>
    </a>
</div>

<?php
        endforeach;
    endif;
    ?>
</div>
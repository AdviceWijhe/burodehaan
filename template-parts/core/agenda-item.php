<?php 
$datum = $args['datum'];
$titel = $args['titel'];
$url = $args['url'];

?>

<div class="agenda-item w-full flex items-stretch border border-blue/20">
  <div class="agenda-item__date aspect-square w-[5.125rem] lg:w-[9.375rem] bg-blue flex flex-col items-center justify-center text-center text-white">
    <?php
    // Converteer datum naar gewenst formaat
    // Parseer datum als DD/MM/YYYY formaat
    $date_obj = DateTime::createFromFormat('d/m/Y', $datum);
    if (!$date_obj) {
        // Fallback: probeer andere formaten als DD/MM/YYYY niet werkt
        $date_obj = new DateTime($datum);
    }
    $dag_naam = strtoupper($date_obj->format('D')); // Korte dag naam (MON, TUE, etc.)
    $dag_nummer = $date_obj->format('j'); // Dag zonder leading zero
    $maand_naam = strtoupper($date_obj->format('M')); // Korte maand naam (JAN, FEB, etc.)
    
    // Nederlandse vertalingen
    $dagen_nl = [
        'MON' => 'MA',
        'TUE' => 'DI', 
        'WED' => 'WO',
        'THU' => 'DO',
        'FRI' => 'VRIJ',
        'SAT' => 'ZA',
        'SUN' => 'ZO'
    ];
    
    $maanden_nl = [
        'JAN' => 'JANUARI',
        'FEB' => 'FEBRUARI', 
        'MAR' => 'MAART',
        'APR' => 'APRIL',
        'MAY' => 'MEI',
        'JUN' => 'JUNI',
        'JUL' => 'JULI',
        'AUG' => 'AUGUSTUS',
        'SEP' => 'SEPTEMBER',
        'OCT' => 'OKTOBER',
        'NOV' => 'NOVEMBER',
        'DEC' => 'DECEMBER'
    ];
    
    $dag_nl = $dagen_nl[$dag_naam] ?? $dag_naam;
    $maand_nl = $maanden_nl[$maand_naam] ?? $maand_naam;
    ?>
    <div class=""><?= $dag_nl ?></div>
    <div class="text-4xl font-medium "><?= $dag_nummer ?></div>
    <div class=" "><?= $maand_nl ?></div>
  </div>
  <div class="agenda-item__title flex-1 p-[1.75rem] lg:p-8 bg-white">
    <h3 class="mb-5 headline-small">Onze volgende excursie</h3>
    <a href="<?= $url ?>" class="btn btn-red inline-flex items-center gap-2">Meld je aan <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11" viewBox="0 0 12 11" fill="none">
  <path d="M12 5.5L10.9482 6.55078L6.74316 10.7568L5.69238 9.70605L9.20801 6.19043H0V4.7041H9.10156L5.69238 1.29492L6.74316 0.243164L12 5.5Z" fill="white"/>
</svg></a>
  </div>
</div>
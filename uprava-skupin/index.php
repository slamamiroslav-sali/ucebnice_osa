<?php
$items=json_decode(@file_get_contents(__DIR__.'/menu.json'),true);
if(!is_array($items)) $items=[];
function e($value){return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');}
function appCount($count){
 return $count.' '.($count===1?'aplikace':($count>=2&&$count<=4?'aplikace':'aplikací'));
}
$groups=[];
foreach($items as $item){
 $category=trim($item['category']??'');
 if($category==='') $category='Nezařazené';
 $groups[$category][]=$item;
}
$selected=null;
$notFound=false;
if(isset($_GET['skupina'])){
 $requested=$_GET['skupina'];
 if(!is_string($requested)||!isset($groups[$requested])){
  http_response_code(404);
  $notFound=true;
 }else{
  $selected=$requested;
 }
}
$pageTitle=$notFound?'Skupina nenalezena':($selected??'Výukové předměty');
?>
<!doctype html>
<html lang="cs">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <title><?=e($pageTitle)?> | Učíme se IT</title>
 <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
<header>
 <div class="wrap top">
  <a class="logo" href="index.php">&lt;/&gt; Učíme se IT</a>
  <a class="adminlink" href="admin.php">Administrace</a>
 </div>
</header>
<main class="wrap">
 <?php if($notFound):?>
  <div class="hero">
   <span class="eyebrow">VÝUKOVÝ PORTÁL</span>
   <h1>Skupina nenalezena</h1>
   <p>Tato skupina již neexistuje nebo byla přejmenována.</p>
   <a class="back-link" href="index.php">← Zpět na všechny předměty</a>
  </div>
 <?php elseif($selected===null):?>
  <div class="hero">
   <span class="eyebrow">VÝUKOVÝ PORTÁL</span>
   <h1>Co se dnes naučíš?</h1>
   <p>Vyber si předmět. Uvnitř najdeš jeho výukové aplikace a cvičení.</p>
  </div>
  <div class="section"><h2>Vyber si předmět</h2></div>
  <?php if(!$groups):?>
   <p class="muted">Výukové aplikace připravujeme.</p>
  <?php else:?>
   <div class="grid subject-grid">
    <?php foreach($groups as $category=>$groupItems):?>
     <a class="tile subject-tile" href="?skupina=<?=rawurlencode((string)$category)?>">
      <span class="symbol" aria-hidden="true"><?php
       echo $category==='Matematika'?'∑':($category==='Informatika'?'⌘':'▦');
      ?></span>
      <span class="num">VÝUKOVÝ PŘEDMĚT</span>
      <h3><?=e($category)?></h3>
      <p><?=appCount(count($groupItems))?></p>
      <span class="go">Prohlédnout předmět →</span>
     </a>
    <?php endforeach;?>
   </div>
  <?php endif;?>
 <?php else:?>
  <div class="hero subject-hero">
   <a class="back-link" href="index.php">← Všechny předměty</a>
   <h1><?=e($selected)?></h1>
   <p>Vyber si aplikaci a otevři interaktivní cvičení.</p>
  </div>
  <div class="section">
   <h2>Výukové aplikace</h2>
   <span><?=appCount(count($groups[$selected]))?></span>
  </div>
  <div class="grid">
   <?php foreach($groups[$selected] as $i=>$item):?>
    <a class="tile" href="https://ucimeseit.cz/ucebnice/<?=rawurlencode($item['slug'])?>/">
     <span class="symbol" aria-hidden="true"><?=e($item['icon']??'⌘')?></span>
     <span class="num">TÉMA <?=str_pad($i+1,2,'0',STR_PAD_LEFT)?></span>
     <h3><?=e($item['title'])?></h3>
     <p><?=e($item['description']??'Otevřít výukovou aplikaci')?></p>
     <span class="go">Otevřít aplikaci →</span>
    </a>
   <?php endforeach;?>
  </div>
 <?php endif;?>
</main>
<footer class="wrap">Učíme se IT · Výukové materiály a interaktivní aplikace</footer>
</body>
</html>

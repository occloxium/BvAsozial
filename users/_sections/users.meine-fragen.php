<?php
  if(CONSTANTS_LOADED && login_check($mysqli)) :
 ?>
<div class="mdl-cell fragen befreundet mdl-color--white mdl-shadow--2dp mdl-cell--12-col mdl-cell--12-col-desktop">
   <p class="mdl-typography--title">Meine Fragen</p>
   <p class="mdl-typography--body-1">
     Du kannst hier deine eigenen Fragen beantworten. Die findest du auch unter <a href="/fragen/#meine-fragen">Fragen</a>. <br />
     Freundesfragen kannst du ebenfalls von dort bearbeiten.
   </p>

       <?php
         require(ABS_PATH . INC_PATH . '/frage.php');
         $j = 1;
         if(count($json['eigeneFragen']) > 0) :
           echo "<ul>";
           foreach($json['eigeneFragen'] as $frage){
             if(!isset($frage['antwort'])){
               $frage['antwort'] = null;
             }
             echo frage($frage, $user, null, $j, 0);
             $j++;
           }
           echo "</ul>";
         else :
           ?>
           <ul class="mdl-list list--no-entries list--border-bottom list--flex-spacer">
             <p class="mdl-typography--body">
               Du hast noch keine Fragen ausgewählt. Wähle welche aus! <a href="/fragen/edit/eigene-fragen">Eigene Fragen bearbeiten</a>
             </p>
           </ul>
           <?php
         endif;
       ?>
   </ol>
 </div>
<?php
  else : echo "";
  endif;
 ?>

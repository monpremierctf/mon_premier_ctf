<?php

 
$string = file_get_contents("./db/challenges.json");
$challenges = json_decode($string, true);
$string = file_get_contents("db/flags.json");
$flags = json_decode($string, true);
$string = file_get_contents("db/files.json");
$files = json_decode($string, true);


function getChallengeCount(){
  global $challenges;
  return $challenges['count'];
}

function dumpChallengesNames(){
  global $challenges;
  foreach ($challenges['results'] as $c) {
    print_r($c['id']);
    print("\n");
    print_r($c['name']);
    print("\n");
  }
}


function isFlagValid($id, $flag){
  global $flags;
  foreach ($flags['results'] as $f) {
    if ($f['challenge_id']==$id) {
      //print 'Found id '.$f['content']." ".$flag."\n";
      if (strcasecmp($f['content'],$flag)==0) {
        return true;
      }
    } 
  }  
  print 'bof';  
  return false;
}


function getCategories(){
  global $challenges;
  $categories = array();
  foreach ($challenges['results'] as $c) {
    if (!in_array($c['category'], $categories)) {
      $categories[] = $c['category'];
    }
    
  }
  return $categories;
}

function debug() {
  global $challenges;
  global $flags;
  global $files;

  print_r($files);

  print_r($files['count']);
  print_r($files['results'][0]);
  foreach ($files['results'] as $f) {
      print_r($f['id']);
      print("\n");
      print_r($f['location']);
      print("\n");
  }
  print getChallengeCount();
}

function html_dump_cat($cat) {
  global $challenges;
  global $Parsedown;

  foreach ($challenges['results'] as $c) {
    if ($c['category']==$cat) {
      print '<div class="container">';
        
        // titre
        print '<div class="row chall-titre bg-secondary text-white">';
        print ($c['name']);
        print "</div>";
        // Description
        print '<div class="container chall-desc">';
        print $Parsedown->text($c['description']);
        print "</div>";
        // Flag
        print '<div class="row chall-desc bg-light">';
        print '
            <div class="col-md-auto text-left"><label for="usr">Flag:</label></div>
            <div class="col text-left"><input type="text" class="form-control" id="flag_'.$c['id'].'" name="code"></div>
            <div class="col-2 text-right"><button type="Submit" class="btn btn-primary" onclick="ctf_validate('.$c['id'].', \'#flag_'.$c['id'].'\')">Submit</button></div>';
        print "</div>";
        print '<div class="row chall-spacer">  </div>';
      print "</div>";
    }
  }     
}
?>
<?php

 // this file has browser detection functions.

 /*
  *  Copyright (c) 2006 Efstathios Chatzikyriakidis (stathis.chatzikyriakidis@gmail.com)
  *
  *  Permission is granted to copy, distribute and/or modify this document
  *  under the terms of the GNU Free Documentation License, Version 1.2 or
  *  any later version published by the Free Software Foundation; with no
  *  Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts. A
  *  copy of the license is included in the section entitled "GNU Free
  *  Documentation License".
  */

 // the following function detects broswer's info.
 function browser_detection ($which_test) 
 {
  // initialize variables.
  $browser_name   = '';
  $browser_number = '';

  // get user agent string.
  $browser_user_agent = (isset ($_SERVER['HTTP_USER_AGENT'])) ? strtolower ($_SERVER['HTTP_USER_AGENT']) : '';

  // pack browser array.
  // values [0] = user agent identifier, lowercase, [1] = dom browser, [2] = shorthand for browser.
  $a_browser_types[] = array ('opera'     , true  , 'op'   );
  $a_browser_types[] = array ('msie'      , true  , 'ie'   );
  $a_browser_types[] = array ('konqueror' , true  , 'konq' );
  $a_browser_types[] = array ('safari'    , true  , 'saf'  );
  $a_browser_types[] = array ('gecko'     , true  , 'moz'  );
  $a_browser_types[] = array ('mozilla/4' , false , 'ns4'  );

  for ($i = 0; $i < count ($a_browser_types); $i++)
  {
   $s_browser    = $a_browser_types[$i][0];
   $b_dom        = $a_browser_types[$i][1];
   $browser_name = $a_browser_types[$i][2];

   // if the string identifier is found in the string.
   if (stristr ($browser_user_agent, $s_browser)) 
   {
    // we are in this case actually searching for the 'rv' string, not the gecko string.
    // this test will fail on Galeon, since it has no rv number. you can change this to 
    // searching for 'gecko' if you want, that will return the release date of the browser.
    if ($browser_name == 'moz')
     $s_browser = 'rv';

    $browser_number = browser_version ($browser_user_agent, $s_browser);
    break;
   }
  }

  // which variable to return
  if ($which_test == 'browser')
  {
   return $browser_name;
  }
  elseif ($which_test == 'number')
  {
   return $browser_number;
  }
  elseif ($which_test == 'full')
  {
   $a_browser_info = array ($browser_name, $browser_number);
   return $a_browser_info;
  }
 }

 // function returns browser number or gecko rv number.
 // this function is called by above function, no need
 // to mess with it unless you want to add more features.
 function browser_version ($browser_user_agent, $search_string)
 {
  // this is the maximum length to search for a version number.
  $string_length = 8;

  // initialize browser number, will return '' if not found.
  $browser_number = '';

  // which parameter is calling it determines what is returned.
  $start_pos = strpos ($browser_user_agent, $search_string);

  // start the substring slice 1 space after the search string.
  $start_pos += strlen ($search_string) + 1;

  // slice out the largest piece that is numeric
  // going down to zero, if zero, function returns ''.
  for ($i = $string_length; $i > 0 ; $i--)
  {
   // is numeric makes sure that the whole substring is a number.
   if (is_numeric (substr ($browser_user_agent, $start_pos, $i)))
   {
    $browser_number = substr ($browser_user_agent, $start_pos, $i);
    break;
   }
  }

  return $browser_number;
 }

 // the following function checks the broswer.
 // if the broswer is `$broswer' then the page
 // exits with a message. otherwise, continues.
 function check_broswer ($browser)
 {
  // detect broswer.
  $browser_name = browser_detection ('browser');

  // check the broswer.
  if ($browser_name == $browser)
   die (PROJECT_INFO . "<br><br>" . $GLOBALS['gui_hello_to_you_text'].
                       "<br><br>" . $GLOBALS['gui_broswer_no_access'].
                       "<br>"     . $GLOBALS['gui_use_any_broswer'].
                       "<br><br>" . $GLOBALS['gui_thank_you_text'].
                       " "        . $GLOBALS['gui_waiting_for_you'].
                       "<br><br>" . $GLOBALS['gui_the_webmaster_text']);

  // otherwise, continues, with no problem.
  return true;
 } 
?>

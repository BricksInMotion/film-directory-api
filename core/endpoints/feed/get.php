<?php
  require_once '../core/common-utils.php';


  function get_data($is_rss=false) {
    require_once '../core/database.php';

    // Default number of films to load
    $total = 4;

    // A different number of films was requested
    if (isset($_GET['total']) && is_numeric($_GET['total'])) {
      $total = intval(escape_xss($_GET['total']));
    }

    // Get the proper SQL script
    $sql = get_sql($is_rss ? 'feed-rss' : 'feed-latest');

    // Get the latest submitted films
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':total', $total, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }


  function json() {
    return make_response(200, get_data($is_rss=false));
  }


  function rss() {
    // Get the film data
    $film_data = get_data($is_rss=true);
    $images_path = get_json('../.login/paths.json')["images"];

    // Create the root tags
    $xml = new DOMDocument('1.0', 'UTF-8');
    $rss_tag = $xml->createElement('rss');
    $rss_tag->setAttribute('version', '2.0');
    $rss_tag->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');
    $channel_tag = $xml->createElement('channel');

    // Create the recommended Atom tags
    $atom_link_tag = $xml->createElement('atom:link');
    $atom_link_tag->setAttribute('href', "https://api.bricksinmotion.com{$_SERVER['REQUEST_URI']}");
    $atom_link_tag->setAttribute('rel', 'self');
    $atom_link_tag->setAttribute('type', 'application/rss+xml');
    $channel_tag->appendChild($atom_link_tag);

    // Create the informational elements
    $title_tag = $xml->createElement('title', 'Bricks in Motion: Newest films');
    $channel_tag->appendChild($title_tag);
    $desc_tag = $xml->createElement('description', 'The latest films submitted at Bricks in Motion.');
    $channel_tag->appendChild($desc_tag);
    $link_tag = $xml->createElement('link', 'https://bricksinmotion.com/films/');
    $channel_tag->appendChild($link_tag);
    $lang_tag = $xml->createElement('language', 'en');
    $channel_tag->appendChild($lang_tag);

    // Create each film listing
    foreach($film_data as $film) {
      $item_tag = $xml->createElement('item');
      $guid_tag = $xml->createElement('guid', "https://bricksinmotion.com/films/view/{$film->id}/");
      $item_tag->appendChild($guid_tag);

      $title_tag = $xml->createElement('title', $film->title);
      $item_tag->appendChild($title_tag);

      $link_tag = $xml->createElement('link', "https://bricksinmotion.com/films/view/{$film->id}/");
      $item_tag->appendChild($link_tag);

      $pubdate_tag = $xml->createElement('pubDate', (new DateTime($film->date))->format('r'));
      $item_tag->appendChild($pubdate_tag);

      // Construct the url and elements for the film thumbnail
      $thumbnail_url = "https://bricksinmotion.com/films/images/{$film->thumbnail}";
      $img_tag = $xml->createElement('img');
      $img_tag->setAttribute('src', $thumbnail_url);
      $img_tag_html = (string) $xml->saveXML($img_tag);
      $text_tag = $xml->createTextNode($img_tag_html);

      // Create the description tag, putting the HTML image tag in it
      $desc_tag = $xml->createElement('description', $film->description);
      $desc_tag->appendChild($text_tag);
      $item_tag->appendChild($desc_tag);

      // Create an enclosure tag for possible image displaying
      $enclosure_tag = $xml->createElement('enclosure');
      $enclosure_tag->setAttribute('url', $thumbnail_url);
      $enclosure_tag->setAttribute('length', filesize("{$images_path}/{$film->thumbnail}"));
      $enclosure_tag->setAttribute('type', 'image/jpeg');
      $item_tag->appendChild($enclosure_tag);
      $channel_tag->appendChild($item_tag);
    }

    // Glue the whole document together
    $rss_tag->appendChild($channel_tag);
    $xml->appendChild($rss_tag);

    // Get a string verson of the document
    $data = (string) $xml->saveXML();
    return make_response(200, $data, $headers=null, $is_xml=true);
  }

  // An RSS feed has been requested
  if (isset($_GET['format']) && escape_xss($_GET['format']) === 'rss') {
    echo rss();

  // If RSS is not explictly requested
  // or the format is not specified, default to JSON
  } else {
    echo json();
  }
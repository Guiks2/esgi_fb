<?php   
function uploadPhoto($session, $id_user){
    if($_POST['album_id'] == -1){
        $album_id = createAlbum($_POST['new_album_name'], $session, $id_user);
    } else{
        $album_id = $_POST['album_id'];
    }
    
    $curlFile = array('source' => new CURLFile($_FILES['photo']['tmp_name'], $_FILES['photo']['type']));
    try {
        $up = new FacebookRequest ($session, 'POST', '/'.$album_id.'/photos', $curlFile);
        $up->execute()->getGraphObject("Facebook\GraphUser");
    } catch (FacebookApiException $e) {
        error_log($e);
    }
}

function createAlbum($name, $session, $id){
    $albums = getAlbums($session, $id);
    if ($albums) {
        for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
            if ($albums->getProperty('data')->getProperty($i)->getProperty('name') == $name) {
                $album_id = $albums->getProperty('data')->getProperty($i)->getProperty('id');
                break;
            } else {
                $album_id = 'blank';
            }
        }
    }
    
    // if the album is not present, create the album
    if ($album_id == 'blank') {
        $album_data = array('name' => $_POST['new_album_name'], 'message' => $album_description, );
    
        $new_album = new FacebookRequest ($session, 'POST', '/'.$id.'/albums', $album_data);
        $new_album = $new_album->execute()->getGraphObject("Facebook\GraphUser");
        $album_id = $new_album->getProperty('id');
    }
    
    return $album_id;
}

function getAlbums($session, $id){
    $request = new FacebookRequest($session, 'GET', '/' . $id . '/albums');
    $response = $request->execute();
    $albums = $response->getGraphObject();
    
    return $albums;
}

// Si $album_id est null, affiche les photos de tous les albums
function getPhotos($session, $id_user, $album_id) {
    
    $albums = getAlbums($session, $id_user);
    for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
        $album = $albums->getProperty('data')->getProperty($i);
        $request = new FacebookRequest($session, 'GET', '/'.$album->getProperty('id').'/photos');
        $response = $request->execute();
        $photos = $response->getGraphObject();

        for ($j = 0; null !== $photos->getProperty('data')->getProperty($j); $j++) {
            if($album_id == null || $album_id == $album->getProperty('id')){
                $photo[] = $photos->getProperty('data')->getProperty($j);
            }
        }
    }
    return $photo;
}
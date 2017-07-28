<?php
namespace AppBundle\Service;

class DeleteImage {

    public function deleteImageWhenObservationDeleted($observation, $dir){
        if($observation->getImage() !== null){
            $fichier = $dir . "/" . $observation->getImage()->getUrl();
            unlink($fichier);
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Ad;
use App\Domain\Picture;

final class InFileSystemPersistence
{
    private array $ads = [];
    private array $pictures = [];

    /**
     * @return array
     */
    public function getAds(): array
    {
        return $this->ads;
    }

    /**
     * @param array $ads
     */
    public function setAds(array $ads): void
    {
        $this->ads = $ads;
    }

    /**
     * @return array
     */
    public function getPictures(): array
    {
        return $this->pictures;
    }

    /**
     * @param array $pictures
     */
    public function setPictures(array $pictures): void
    {
        $this->pictures = $pictures;
    }

    public function __construct()
    {
        array_push($this->ads, new Ad(1, 'CHALET', 'Este piso es una ganga, compra, compra, COMPRA!!!!!', [], 300, null, null, null));
        array_push($this->ads, new Ad(2, 'FLAT', 'Nuevo ático céntrico recién reformado. No deje pasar la oportunidad y adquiera este ático de lujo', [4], 300, null, null, null));
        array_push($this->ads, new Ad(3, 'CHALET', '', [2], 300, null, null, null));
        array_push($this->ads, new Ad(4, 'FLAT', 'Ático céntrico muy luminoso y recién reformado, parece nuevo', [5], 300, null, null, null));
        array_push($this->ads, new Ad(5, 'FLAT', 'Pisazo,', [3, 8], 300, null, null, null));
        array_push($this->ads, new Ad(6, 'GARAGE', '', [6], 300, null, null, null));
        array_push($this->ads, new Ad(7, 'GARAGE', 'Garaje en el centro de Albacete', [], 300, null, null, null));
        array_push($this->ads, new Ad(8, 'CHALET', 'Maravilloso chalet situado en lAs afueras de un pequeño pueblo rural. El entorno es espectacular, las vistas magníficas. ¡Cómprelo ahora!', [1, 7], 300, null, null, null));

        array_push($this->pictures, new Picture(1, 'https://i.blogs.es/a19bfc/testing/450_1000.jpg', 'SD'));
        array_push($this->pictures, new Picture(2, 'https://st1.idealista.com/static/common/release/home/resources/img/mortgages/es/es/mortgages-small-devices.jpg?20211126113225', 'HD'));
        array_push($this->pictures, new Picture(3, 'https://st1.idealista.com/static/common/release/home/resources/img/rentalia/es/rentalia-small-devices-winter.jpg?20211126113225', 'SD'));
        array_push($this->pictures, new Picture(4, 'https://st3.idealista.com/news/archivos/styles/idn_home_article_media_mobile/public/2017-04/oportunidad.jpg?sv=CuyUdCnJ&itok=2ERr5ENc', 'HD'));
        array_push($this->pictures, new Picture(5, 'https://st1.idealista.com/static/common/release/home/resources/img/maps/es/es/maps-small-devices.jpg?20211126113225', 'SD'));
        array_push($this->pictures, new Picture(6, 'https://st3.idealista.com/news/archivos/styles/news_detail/public/2017-04/oportunidad.jpg?sv=Lnri66il&itok=Y2Tqbj-q', 'SD'));
        array_push($this->pictures, new Picture(7, 'https://img3.idealista.com/blur/WEB_DETAIL-S-L/0/id.pro.es.image.master/a8/ef/bc/925959049.jpg', 'SD'));
        array_push($this->pictures, new Picture(8, 'https://img3.idealista.com/blur/WEB_DETAIL-S-L/0/id.pro.es.image.master/d6/af/c1/787906518.jpg', 'HD'));
    }

    /**
     * @param int $id
     * @return Picture|null
     */
    public function findPictureById($id): Picture
    {
        $encontrado = null;
        foreach ($this->pictures as $picture) {
            if ($picture->getId() == $id) {
                $encontrado = $picture;
                break;
            }
        }
        return $encontrado;
    }
}

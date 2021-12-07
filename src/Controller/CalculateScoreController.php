<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Persistence\InFileSystemPersistence;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CalculateScoreController extends AbstractController
{
    private InFileSystemPersistence $fileSystem;

    public function __construct()
    {
        $this->fileSystem = new InFileSystemPersistence();
    }


    public function __invoke($array): JsonResponse
    {
        return new JsonResponse(['list' => $array]);
    }


    /**
     * @Route("/adminlist", name="adminlist")
     * @return Response
     */
    public function adminListAction()
    {
        $list = $this->getScoreAndDate();
        $index = 0;
        foreach ($list as $ad) {
            if ($ad['ad']->getScore() >= 40) {
                unset($list[$index]);
            }
            $index++;
        }
        $response = $this->render('ad.html.twig', ['list' => $list]);
        return $response;
    }

    /**
     * @Route("/publiclist", name="publiclist")
     * @return Response
     */
    public function publicListAction()
    {
        $list = $this->getScoreAndDate();
        $response = $this->render('ad.html.twig', ['list' => $list, 'public' => 1]);
        return $response;
    }

    /**
     * @Route("/full-list", name="full_list")
     * @return Response
     */
    public function fullListAction()
    {
        $list = $this->getScoreAndDate();
        $response = $this->render('ad.html.twig', ['list' => $list]);
        return $response;
    }


    function comp($ad1, $ad2) {
        if ($ad1['ad']->getScore() == $ad2['ad']->getScore()) {
            return 0;
        } elseif ($ad1['ad']->getScore() > $ad2['ad']->getScore()) {
            return -1;
        } else {
            return 1;
        }
    }

    public function getScoreAndDate()
    {
        $ads = $this->fileSystem->getAds();
        $list = [];

        foreach ($ads as $ad) {
           $ad->setScore();
           $pictures = [];
            foreach ($ad->getPictures() as $picture) {
                $pictures[] = $this->fileSystem->findPictureById($picture);
            }
           $list[] = ['ad' => $ad, 'pictures' => $pictures];
        }
        usort($list, array($this,'comp'));
        return $list;
    }

}

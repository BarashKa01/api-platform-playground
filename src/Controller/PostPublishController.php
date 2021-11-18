<?php


namespace App\Controller;


use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostPublishController extends AbstractController
{

    public function __invoke(Post $data): Post
    {
        $data->setOnline(true);
        return $data;
    }

}